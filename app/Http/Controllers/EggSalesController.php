<?php

namespace App\Http\Controllers;

use App\Models\EggContact;
use App\Models\EggDailyProduction;
use App\Models\EggOrder;
use App\Models\EggSaleSetting;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EggSalesController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $selectedDate = $request->date('date')?->toDateString() ?? now()->toDateString();
        $month = CarbonImmutable::parse($request->string('month')->value() ?: $selectedDate)->startOfMonth();
        $rangeStart = $month->startOfMonth()->startOfWeek();
        $rangeEnd = $month->endOfMonth()->endOfWeek();

        $orders = EggOrder::query()
            ->with(['contact', 'creator'])
            ->whereBetween('order_date', [$rangeStart, $rangeEnd])
            ->orderBy('created_at')
            ->get()
            ->groupBy(fn (EggOrder $order) => $order->order_date->toDateString());
        $productions = EggDailyProduction::query()
            ->whereBetween('production_date', [$rangeStart, $rangeEnd])
            ->get()
            ->keyBy(fn (EggDailyProduction $production) => $production->production_date->toDateString());

        $openingBalance = (int) EggDailyProduction::query()
            ->whereDate('production_date', '<', $rangeStart)->sum('quantity')
            - (int) EggOrder::query()->whereNull('cancelled_at')->whereDate('order_date', '<', $rangeStart)->sum('quantity');
        $dailyStats = [];
        $runningBalance = $openingBalance;
        for ($day = $rangeStart; $day->lte($rangeEnd); $day = $day->addDay()) {
            $date = $day->toDateString();
            $dayOrders = $orders->get($date, collect());
            $produced = (int) ($productions->get($date)?->quantity ?? 0);
            $activeOrders = $dayOrders->whereNull('cancelled_at');
            $ordered = (int) $activeOrders->sum('quantity');
            $collected = (int) $activeOrders->where('is_collected', true)->sum('quantity');
            $dailyStats[$date] = [
                'opening' => $runningBalance,
                'produced' => $produced,
                'ordered' => $ordered,
                'collected' => $collected,
                'pending' => $ordered - $collected,
                'closing' => $runningBalance + $produced - $ordered,
            ];
            $runningBalance = $dailyStats[$date]['closing'];
        }

        $statsValues = $request->validate([
            'stats_from' => ['nullable', 'date'],
            'stats_to' => ['nullable', 'date', 'after_or_equal:stats_from'],
        ]);
        $statsFrom = CarbonImmutable::parse($statsValues['stats_from'] ?? $month->startOfMonth())->startOfDay();
        $statsTo = CarbonImmutable::parse($statsValues['stats_to'] ?? ($statsValues['stats_from'] ?? $month->endOfMonth()))->startOfDay();
        $statsProductions = EggDailyProduction::query()
            ->whereBetween('production_date', [$statsFrom, $statsTo])
            ->pluck('quantity', 'production_date');
        $statsSales = EggOrder::query()
            ->whereNull('cancelled_at')
            ->where('is_collected', true)
            ->whereBetween('order_date', [$statsFrom, $statsTo])
            ->selectRaw('order_date, SUM(quantity) as quantity, SUM(total_price) as revenue')
            ->groupBy('order_date')
            ->get()
            ->keyBy(fn (EggOrder $order) => $order->order_date->toDateString());
        $statsChart = [];
        for ($day = $statsFrom; $day->lte($statsTo); $day = $day->addDay()) {
            $date = $day->toDateString();
            $statsChart[] = [
                'date' => $date,
                'label' => $day->format('d/m'),
                'produced' => (int) ($statsProductions[$date] ?? 0),
                'sold' => (int) ($statsSales->get($date)?->quantity ?? 0),
            ];
        }

        return view('admin.egg-sales', [
            'month' => $month,
            'selectedDate' => $selectedDate,
            'monthOrders' => $orders,
            'dayOrders' => $orders->get($selectedDate, collect()),
            'dayStats' => $dailyStats[$selectedDate] ?? $this->statsForDate($selectedDate),
            'dailyStats' => $dailyStats,
            'contacts' => EggContact::query()->orderBy('last_name')->orderBy('first_name')->get(),
            'settings' => EggSaleSetting::current(),
            'production' => $productions->get($selectedDate),
            'statsFrom' => $statsFrom,
            'statsTo' => $statsTo,
            'statsProduced' => array_sum(array_column($statsChart, 'produced')),
            'statsSold' => array_sum(array_column($statsChart, 'sold')),
            'statsRevenue' => (float) $statsSales->sum('revenue'),
            'statsChart' => $statsChart,
        ]);
    }

    public function storeContact(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);
        EggContact::create($request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:40'],
        ]));

        return back()->with('success', 'Contatto aggiunto alla rubrica.');
    }

    public function destroyContact(Request $request, EggContact $eggContact): RedirectResponse
    {
        $this->ensureAdmin($request);
        if ($eggContact->orders()->exists()) {
            throw ValidationException::withMessages(['contact' => 'Il contatto ha degli ordini e non può essere eliminato.']);
        }
        $eggContact->delete();

        return back()->with('success', 'Contatto eliminato.');
    }

    public function storeOrder(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);
        $values = $request->validate([
            'egg_contact_id' => ['required', 'exists:egg_contacts,id'],
            'order_date' => ['required', 'date'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        $unitPrice = (float) EggSaleSetting::current()->unit_price;
        EggOrder::create([
            ...$values,
            'created_by_user_id' => $request->user()->getKey(),
            'unit_price' => $unitPrice,
            'total_price' => round($unitPrice * (int) $values['quantity'], 2),
        ]);

        return $this->dayRedirect($values['order_date'], 'Ordine di uova salvato.');
    }

    public function toggleCollected(Request $request, EggOrder $eggOrder): RedirectResponse
    {
        $this->ensureAdmin($request);
        abort_if($eggOrder->cancelled_at, 422, 'Un ordine annullato non può essere segnato come ritirato.');
        $eggOrder->forceFill([
            'is_collected' => ! $eggOrder->is_collected,
            'collected_at' => $eggOrder->is_collected ? null : now(),
        ])->save();

        return $this->dayRedirect($eggOrder->order_date->toDateString(), $eggOrder->is_collected ? 'Ordine segnato come ritirato.' : 'Ordine riaperto.');
    }

    public function toggleCancelled(Request $request, EggOrder $eggOrder): RedirectResponse
    {
        $this->ensureAdmin($request);
        $isReopening = $eggOrder->cancelled_at !== null;
        $eggOrder->forceFill([
            'cancelled_at' => $isReopening ? null : now(),
            'is_collected' => $isReopening ? $eggOrder->is_collected : false,
            'collected_at' => $isReopening ? $eggOrder->collected_at : null,
        ])->save();

        return $this->dayRedirect(
            $eggOrder->order_date->toDateString(),
            $isReopening ? 'Ordine ripristinato e disponibilità ricalcolata.' : 'Ordine annullato e disponibilità ricalcolata.',
        );
    }

    public function destroyOrder(Request $request, EggOrder $eggOrder): RedirectResponse
    {
        $this->ensureAdmin($request);
        $date = $eggOrder->order_date->toDateString();
        $eggOrder->delete();

        return $this->dayRedirect($date, 'Ordine eliminato e disponibilità ricalcolata.');
    }

    public function saveProduction(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);
        $values = $request->validate([
            'production_date' => ['required', 'date'],
            'quantity' => ['required', 'integer', 'min:0', 'max:10000'],
        ]);
        EggDailyProduction::updateOrCreate(
            ['production_date' => $values['production_date']],
            ['quantity' => $values['quantity']],
        );

        return $this->dayRedirect($values['production_date'], 'Produzione giornaliera aggiornata.');
    }

    public function saveSettings(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);
        $values = $request->validate(['unit_price' => ['required', 'numeric', 'min:0', 'max:9999']]);
        EggSaleSetting::current()->update($values);

        return back()->with('success', 'Prezzo per uovo aggiornato. I nuovi ordini useranno questo importo.');
    }

    private function statsForDate(string $date): array
    {
        $opening = (int) EggDailyProduction::query()->whereDate('production_date', '<', $date)->sum('quantity')
            - (int) EggOrder::query()->whereNull('cancelled_at')->whereDate('order_date', '<', $date)->sum('quantity');
        $produced = (int) EggDailyProduction::query()->whereDate('production_date', $date)->value('quantity');
        $orders = EggOrder::query()->whereNull('cancelled_at')->whereDate('order_date', $date);
        $ordered = (int) (clone $orders)->sum('quantity');
        $collected = (int) (clone $orders)->where('is_collected', true)->sum('quantity');

        return compact('opening', 'produced', 'ordered', 'collected') + [
            'pending' => $ordered - $collected,
            'closing' => $opening + $produced - $ordered,
        ];
    }

    private function dayRedirect(string $date, string $message): RedirectResponse
    {
        return redirect()->to(route('uova.index', ['date' => $date, 'month' => substr($date, 0, 7)]).'#giorno')
            ->with('success', $message);
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()?->is_admin || $request->user()?->is_super_admin, 403);
    }
}
