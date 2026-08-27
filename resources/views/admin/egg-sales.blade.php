<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <meta name="theme-color" content="#78652d">
    <title>Vendita uova · LAMAKA</title>
    <style>
        :root{--ink:#29261f;--muted:#756f64;--yolk:#e3a824;--olive:#6f6a45;--paper:#f5f0e7;--line:#d8cebc;--red:#a43c31;--green:#3f6850;--white:#fffdfa}*{box-sizing:border-box}body{margin:0;background:var(--paper);color:var(--ink);font:15px/1.45 Inter,system-ui,sans-serif}.shell{max-width:1240px;margin:auto;padding:20px}.top{display:flex;align-items:center;justify-content:space-between;gap:18px;margin-bottom:22px}.top img{width:112px}.top nav{display:flex;gap:16px}.top a{color:var(--olive);font-weight:700}h1,h2,h3{font-family:Georgia,serif;font-weight:500;margin-top:0}.intro{display:flex;align-items:end;justify-content:space-between;gap:20px;margin-bottom:18px}.intro h1{font-size:clamp(30px,5vw,48px);margin:0}.intro p{color:var(--muted);margin:4px 0}.layout{display:grid;grid-template-columns:minmax(0,1.55fr) minmax(310px,.72fr);gap:22px}.stack{display:grid;gap:22px}.card{background:#fff9;border:1px solid var(--line);padding:20px;border-radius:18px;box-shadow:0 9px 28px #2f2a240d}.monthbar{display:flex;align-items:center;justify-content:space-between}.monthbar h2{margin:0}.monthbar a{font-size:28px;color:var(--olive);text-decoration:none;padding:5px 12px}.week,.calendar{display:grid;grid-template-columns:repeat(7,minmax(0,1fr));gap:6px}.week div{text-align:center;color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:.08em;padding:10px 0}.day{min-height:104px;border:1px solid var(--line);border-radius:11px;padding:8px;text-decoration:none;color:inherit;background:var(--white);overflow:hidden}.day.muted{opacity:.38}.day.selected{outline:3px solid #e3a82488}.day.negative{background:#fff0ed;border-color:#dca29b}.num{font-weight:800}.day-balance{float:right;font-size:11px;font-weight:800;color:var(--green)}.day-balance.neg{color:var(--red)}.dot{display:block;margin-top:5px;padding:3px 5px;border-radius:5px;font-size:11px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;background:#f5d5d0;color:var(--red)}.dot.done{background:#dce8df;color:var(--green);text-decoration:line-through}.summary{display:grid;grid-template-columns:repeat(5,1fr);gap:8px;margin:16px 0}.metric{background:#f7f2e9;border-radius:12px;padding:12px}.metric b{display:block;font-size:23px}.metric span{font-size:11px;color:var(--muted);text-transform:uppercase}.metric.final{background:#e5eddf;color:var(--green)}.metric.final.negative{background:#f5d5d0;color:var(--red)}.order{display:grid;grid-template-columns:1fr auto;gap:10px;padding:13px;margin:10px 0;border-left:5px solid var(--red);background:#fff1ef;border-radius:7px}.order.done{border-color:var(--green);background:#edf4ef;color:#4f574f}.order strong{font-size:16px}.order small{display:block;color:var(--muted)}.actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}.link-button{border:0;background:none;color:var(--olive);padding:0;text-decoration:underline;font:inherit;font-weight:800;cursor:pointer}.danger{color:var(--red)}label{display:block;margin:12px 0 5px;font-size:11px;text-transform:uppercase;letter-spacing:.07em;color:#655f57}input,select,textarea{width:100%;padding:11px 12px;border:1px solid var(--line);border-radius:9px;background:#fff;font:inherit}textarea{min-height:70px}.two{display:grid;grid-template-columns:1fr 1fr;gap:11px}.button{width:100%;border:0;border-radius:10px;background:var(--olive);color:#fff;padding:13px;margin-top:15px;font-weight:800;cursor:pointer}.button.yolk{background:#a87500}.flash,.errors{padding:12px 14px;border-radius:10px;margin-bottom:16px}.flash{background:#dce8df;color:#284a37}.errors{background:#f5d5d0;color:#76271f}.contact{display:flex;align-items:center;justify-content:space-between;gap:12px;border-top:1px solid var(--line);padding:10px 0}.contact:first-of-type{border-top:0}.contact small{display:block;color:var(--muted)}.empty{color:var(--muted);font-style:italic}.price-preview{padding:11px;background:#f3ead4;border-radius:9px;margin-top:11px;text-align:center}.tabs{display:flex;gap:6px;margin-bottom:14px}.tabs a{color:var(--olive);background:#ebe4d8;border-radius:20px;padding:7px 12px;text-decoration:none;font-weight:700}.help{font-size:12px;color:var(--muted)}@media(max-width:850px){.shell{padding:14px}.layout{grid-template-columns:1fr}.summary{grid-template-columns:repeat(2,1fr)}.metric.final{grid-column:span 2}.day{min-height:68px;padding:5px}.week,.calendar{gap:3px}.dot{font-size:0;width:8px;height:8px;padding:0;border-radius:50%}.day-balance{font-size:9px}.two{grid-template-columns:1fr}.order{grid-template-columns:1fr}.top nav{font-size:12px}.intro{align-items:start;flex-direction:column}}
    </style>
</head>
<body>
@php
    $start = $month->startOfMonth()->startOfWeek(\Carbon\CarbonInterface::MONDAY);
    $end = $month->endOfMonth()->endOfWeek(\Carbon\CarbonInterface::SUNDAY);
@endphp
<main class="shell">
    <header class="top">
        <img src="/logo.png" alt="LAMAKA">
        <nav><a href="/agenda">Agenda</a><a href="/admin">Amministrazione</a></nav>
    </header>
    <div class="intro"><div><h1>Vendita uova</h1><p>Ordini, ritiri e disponibilità in un unico calendario.</p></div><div class="tabs"><a href="#rubrica">Rubrica</a><a href="#impostazioni">Impostazioni</a></div></div>

    @if(session('success'))<div class="flash">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="errors"><strong>Controlla i dati inseriti:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <div class="layout">
        <section class="stack">
            <div class="card">
                <div class="monthbar">
                    <a aria-label="Mese precedente" href="{{ route('uova.index',['month'=>$month->subMonth()->format('Y-m'),'date'=>$month->subMonth()->startOfMonth()->toDateString()]) }}">‹</a>
                    <h2>{{ ucfirst($month->locale('it')->translatedFormat('F Y')) }}</h2>
                    <a aria-label="Mese successivo" href="{{ route('uova.index',['month'=>$month->addMonth()->format('Y-m'),'date'=>$month->addMonth()->startOfMonth()->toDateString()]) }}">›</a>
                </div>
                <div class="week">@foreach(['Lun','Mar','Mer','Gio','Ven','Sab','Dom'] as $weekday)<div>{{ $weekday }}</div>@endforeach</div>
                <div class="calendar">
                    @for($day=$start; $day->lte($end); $day=$day->addDay())
                        @php($date=$day->toDateString()) @php($stats=$dailyStats[$date]) @php($items=$monthOrders->get($date,collect()))
                        <a class="day {{ !$day->isSameMonth($month)?'muted':'' }} {{ $date===$selectedDate?'selected':'' }} {{ $stats['closing']<0?'negative':'' }}" href="{{ route('uova.index',['date'=>$date,'month'=>$day->format('Y-m')]) }}#giorno">
                            <span class="num">{{ $day->day }}</span><span class="day-balance {{ $stats['closing']<0?'neg':'' }}">{{ $stats['closing'] }} rim.</span>
                            @foreach($items->take(3) as $item)<span class="dot {{ $item->is_collected?'done':'' }}">{{ $item->contact->full_name }} · {{ $item->quantity }}</span>@endforeach
                        </a>
                    @endfor
                </div>
            </div>

            <div id="giorno" class="card" style="scroll-margin-top:16px">
                <h2>{{ \Carbon\Carbon::parse($selectedDate)->locale('it')->translatedFormat('l d F Y') }}</h2>
                <div class="summary">
                    <div class="metric"><b>{{ $dayStats['opening'] }}</b><span>Riporto iniziale</span></div>
                    <div class="metric"><b>+{{ $dayStats['produced'] }}</b><span>Prodotte</span></div>
                    <div class="metric"><b>{{ $dayStats['ordered'] }}</b><span>Ordinate</span></div>
                    <div class="metric"><b>{{ $dayStats['collected'] }}</b><span>Ritirate</span></div>
                    <div class="metric final {{ $dayStats['closing']<0?'negative':'' }}"><b>{{ $dayStats['closing'] }}</b><span>{{ $dayStats['closing']<0?'Uova mancanti':'Rimanenza finale' }}</span></div>
                </div>
                <p class="help">La rimanenza finale considera tutte le uova ordinate, anche se non ancora ritirate, e viene riportata automaticamente al giorno seguente.</p>

                <h3>Ordini del giorno</h3>
                @forelse($dayOrders as $order)
                    <article class="order {{ $order->is_collected?'done':'' }}">
                        <div><strong>{{ $order->contact->full_name }} · {{ $order->quantity }} uova</strong><small>{{ $order->contact->phone }} · € {{ number_format((float)$order->total_price,2,',','.') }} @if($order->notes) · {{ $order->notes }} @endif</small></div>
                        <div class="actions">
                            <form method="post" action="{{ route('uova.orders.toggle-collected',$order) }}">@csrf @method('PATCH')<button class="link-button" type="submit">{{ $order->is_collected?'↩ Non ritirato':'✓ Segna ritirato' }}</button></form>
                            <form method="post" action="{{ route('uova.orders.destroy',$order) }}" onsubmit="return confirm('Eliminare questo ordine?')">@csrf @method('DELETE')<button class="link-button danger" type="submit">Elimina</button></form>
                        </div>
                    </article>
                @empty <p class="empty">Nessun ordine per questa giornata.</p> @endforelse
            </div>
        </section>

        <aside class="stack">
            <div class="card">
                <h2>Produzione del giorno</h2>
                <form method="post" action="{{ route('uova.production.save') }}">@csrf @method('PUT')
                    <input type="hidden" name="production_date" value="{{ $selectedDate }}">
                    <label for="production_quantity">Uova prodotte</label><input id="production_quantity" type="number" name="quantity" min="0" required value="{{ old('quantity',$production?->quantity ?? 0) }}">
                    <button class="button" type="submit">Aggiorna produzione</button>
                </form>
            </div>

            <div class="card">
                <h2>Nuovo ordine</h2>
                @if($contacts->isEmpty())<p class="empty">Aggiungi prima un nominativo alla rubrica.</p>@endif
                <form method="post" action="{{ route('uova.orders.store') }}">@csrf
                    <input type="hidden" name="order_date" value="{{ $selectedDate }}">
                    <label for="egg_contact_id">Cliente</label><select id="egg_contact_id" name="egg_contact_id" required><option value="">Seleziona dalla rubrica</option>@foreach($contacts as $contact)<option value="{{ $contact->id }}" @selected(old('egg_contact_id')==$contact->id)>{{ $contact->last_name }} {{ $contact->first_name }} · {{ $contact->phone }}</option>@endforeach</select>
                    <label for="order_quantity">Numero di uova</label><input id="order_quantity" type="number" name="quantity" min="1" required value="{{ old('quantity') }}">
                    <div class="price-preview">Prezzo: <strong id="order-total">€ 0,00</strong> <small>(€ {{ number_format((float)$settings->unit_price,2,',','.') }} cad.)</small></div>
                    <label for="notes">Note</label><textarea id="notes" name="notes">{{ old('notes') }}</textarea>
                    <button class="button yolk" type="submit" @disabled($contacts->isEmpty())>Salva ordine</button>
                </form>
            </div>

            <div id="rubrica" class="card" style="scroll-margin-top:16px">
                <h2>Rubrica</h2>
                @foreach($contacts as $contact)<div class="contact"><div><strong>{{ $contact->last_name }} {{ $contact->first_name }}</strong><small><a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a></small></div><form method="post" action="{{ route('uova.contacts.destroy',$contact) }}" onsubmit="return confirm('Eliminare questo contatto?')">@csrf @method('DELETE')<button class="link-button danger" aria-label="Elimina {{ $contact->full_name }}">×</button></form></div>@endforeach
                <form method="post" action="{{ route('uova.contacts.store') }}">@csrf
                    <div class="two"><div><label for="first_name">Nome</label><input id="first_name" name="first_name" required></div><div><label for="last_name">Cognome</label><input id="last_name" name="last_name" required></div></div>
                    <label for="phone">Numero di telefono</label><input id="phone" type="tel" name="phone" required>
                    <button class="button" type="submit">Aggiungi contatto</button>
                </form>
            </div>

            <div id="impostazioni" class="card" style="scroll-margin-top:16px">
                <h2>Impostazioni vendita uova</h2>
                <form method="post" action="{{ route('uova.settings.save') }}">@csrf @method('PUT')
                    <label for="unit_price">Prezzo per singolo uovo (€)</label><input id="unit_price" type="number" name="unit_price" min="0" step="0.01" required value="{{ old('unit_price',$settings->unit_price) }}">
                    <p class="help">La modifica vale per i nuovi ordini. Gli ordini già registrati conservano il prezzo applicato.</p>
                    <button class="button" type="submit">Salva prezzo</button>
                </form>
            </div>
        </aside>
    </div>
</main>
<script>
const quantity=document.getElementById('order_quantity'), total=document.getElementById('order-total'), unitPrice={{ json_encode((float)$settings->unit_price) }};
function updateTotal(){total.textContent=new Intl.NumberFormat('it-IT',{style:'currency',currency:'EUR'}).format((Number(quantity.value)||0)*unitPrice)}
quantity.addEventListener('input',updateTotal);updateTotal();
</script>
</body>
</html>
