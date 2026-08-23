<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <meta name="theme-color" content="#6f6a45">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="LAMAKA Agenda">
    <link rel="manifest" href="{{ route('agenda.manifest') }}">
    <link rel="apple-touch-icon" href="/logo.png">
    <title>Agenda prenotazioni · LAMAKA</title>
    <style>
        :root{--ink:#2f2a24;--olive:#6f6a45;--paper:#f4efe7;--line:#d8cdbd;--red:#a8392e;--green:#45614d}*{box-sizing:border-box}body{margin:0;background:var(--paper);color:var(--ink);font:15px/1.45 Inter,system-ui,sans-serif}.shell{max-width:1180px;margin:auto;padding:20px}.top{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:24px}.top img{width:118px}.top a{color:var(--olive)}h1,h2,h3{font-family:Georgia,serif;font-weight:500}.layout{display:grid;grid-template-columns:minmax(0,1.45fr) minmax(300px,.85fr);gap:24px}.card{background:#fff9;border:1px solid var(--line);padding:20px;border-radius:16px;box-shadow:0 8px 28px #2f2a2410}.monthbar{display:flex;align-items:center;justify-content:space-between}.monthbar a{font-size:25px;color:var(--olive);text-decoration:none;padding:8px}.week,.calendar{display:grid;grid-template-columns:repeat(7,1fr);gap:6px}.week div{text-align:center;font-size:11px;text-transform:uppercase;letter-spacing:.08em;padding:10px 0}.day{min-height:92px;border:1px solid var(--line);border-radius:10px;padding:8px;text-decoration:none;color:inherit;background:#fff;overflow:hidden}.day.muted{opacity:.38}.day.selected{outline:3px solid #6f6a4566}.day.full{background:#eee5dc}.num{font-weight:700}.dot{display:block;margin-top:5px;padding:3px 5px;border-radius:5px;font-size:11px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;background:#dfe9e1;color:var(--green)}.dot.pending{background:#f5d9d5;color:var(--red)}.dot.cancelled{background:#e3e0dc;color:#6f6962;text-decoration:line-through}.booking{border-left:4px solid var(--green);padding:11px 12px;margin:10px 0;background:#f8faf8}.booking.pending{border-color:var(--red);background:#fff1ef}.booking.cancelled{border-color:#777;background:#eeeae5;color:#625d57}.booking a{color:var(--olive);font-weight:700}.badge{font-size:11px;text-transform:uppercase;font-weight:800;color:var(--red)}.badge.cancelled{color:#625d57}.booking-actions{display:flex;flex-wrap:wrap;align-items:center;gap:14px;margin-top:10px}.action-link,.action-button,.cancel-summary{display:inline-flex;align-items:center;gap:5px;border:0;background:none;padding:0;font:inherit;font-weight:800;text-decoration:underline;cursor:pointer}.action-link::before,.action-button::before,.cancel-summary::before{content:'▸';text-decoration:none}.action-confirm{color:var(--green)}.action-edit{color:var(--olive)!important}.cancel-summary,.action-cancel{color:var(--red)}.inline-action{display:inline;margin:0}.cancel-box{margin-top:10px;padding:12px;background:#fff;border:1px solid var(--line);border-radius:8px}.cancel-button{border:0;border-radius:8px;background:var(--red);color:#fff;padding:11px 14px;margin-top:12px;font-weight:800;cursor:pointer}label{display:block;margin:13px 0 5px;font-size:12px;text-transform:uppercase;letter-spacing:.06em;color:#655f57}input,select,textarea{width:100%;padding:11px 12px;border:1px solid var(--line);border-radius:8px;background:#fff;font:inherit}textarea{min-height:95px}.two{display:grid;grid-template-columns:1fr 1fr;gap:12px}.button{width:100%;border:0;border-radius:9px;background:var(--olive);color:white;padding:14px;margin-top:18px;font-weight:800;cursor:pointer}.flash,.errors{padding:12px;border-radius:9px;margin-bottom:14px}.flash{background:#dfe9e1}.errors{background:#f5d9d5;color:#7f2921}.empty{color:#766f65}.capacity{font-weight:700;color:var(--olive)}@media(max-width:800px){.shell{padding:14px}.layout{grid-template-columns:1fr}.day{min-height:66px;padding:5px}.week,.calendar{gap:3px}.dot{font-size:0;height:8px;width:8px;padding:0;border-radius:50%}.card{padding:14px}.two{grid-template-columns:1fr}}
        .cancel-summary{list-style:none}.cancel-summary::-webkit-details-marker{display:none}
        .modal-trigger{border:0;background:none;padding:0;font:inherit}.booking-dialog{width:min(92vw,560px);max-height:88vh;border:1px solid var(--line);border-radius:16px;padding:0;background:var(--paper);color:var(--ink);box-shadow:0 24px 80px #0005;overflow:auto}.booking-dialog::backdrop{background:#2f2a2499;backdrop-filter:blur(3px)}.dialog-inner{padding:22px}.dialog-top{display:flex;align-items:flex-start;justify-content:space-between;gap:16px}.dialog-top h3{font-size:27px;margin:0 0 12px}.dialog-close{border:0;background:#e5ded5;border-radius:50%;width:36px;height:36px;font-size:22px;cursor:pointer;flex:none}.dialog-booking{padding:13px;border-left:4px solid var(--olive);background:#fff;margin:10px 0 16px}.dialog-buttons{display:flex;gap:10px;margin-top:16px}.dialog-buttons button{flex:1;border:0;border-radius:9px;padding:13px;font-weight:800;cursor:pointer}.dialog-confirm{background:var(--green);color:white}.dialog-edit{background:var(--olive);color:white}.dialog-cancel{background:var(--red);color:white}.dialog-back{background:#ddd6cd;color:var(--ink)}@media(max-width:600px){.booking-dialog{width:calc(100vw - 24px);max-height:calc(100vh - 24px)}.dialog-inner{padding:18px}.dialog-buttons{flex-direction:column}}
    </style>
</head>
<body>
@php
    $start = $month->startOfMonth()->startOfWeek(\Carbon\CarbonInterface::MONDAY);
    $end = $month->endOfMonth()->endOfWeek(\Carbon\CarbonInterface::SUNDAY);
    $usedAnimals = (int) $dayBookings->whereNotNull('confirmed_at')->sum('animals');
    $formBooking = $editBooking;
@endphp
<main class="shell">
    <header class="top"><img src="/logo.png" alt="LAMAKA"><div><strong>Agenda</strong> · <a href="/admin">Amministrazione</a></div></header>
    @if(session('success'))<div class="flash">{{ session('success') }}</div>@endif
    <div class="layout">
        <section>
            <div class="card">
                <div class="monthbar">
                    <a aria-label="Mese precedente" href="{{ route('agenda.index', ['month'=>$month->subMonth()->format('Y-m'), 'date'=>$month->subMonth()->startOfMonth()->toDateString()]) }}">‹</a>
                    <h1>{{ ucfirst($month->locale('it')->translatedFormat('F Y')) }}</h1>
                    <a aria-label="Mese successivo" href="{{ route('agenda.index', ['month'=>$month->addMonth()->format('Y-m'), 'date'=>$month->addMonth()->startOfMonth()->toDateString()]) }}">›</a>
                </div>
                <div class="week">@foreach(['Lun','Mar','Mer','Gio','Ven','Sab','Dom'] as $weekday)<div>{{ $weekday }}</div>@endforeach</div>
                <div class="calendar">
                    @for($day=$start; $day->lte($end); $day=$day->addDay())
                        @php
                            $date = $day->toDateString(); $items = $monthBookings->get($date, collect());
                            $total = (int) $items->whereNotNull('confirmed_at')->sum('animals');
                        @endphp
                        <a class="day {{ !$day->isSameMonth($month)?'muted':'' }} {{ $date===$selectedDate?'selected':'' }} {{ $total>=5?'full':'' }}" href="{{ route('agenda.index',['date'=>$date,'month'=>$day->format('Y-m')]) }}#day-details">
                            <span class="num">{{ $day->day }}</span>
                            @foreach($items->take(3) as $item)<span class="dot {{ $item->cancelled_at?'cancelled':($item->confirmed_at?'':'pending') }}">{{ $item->customer_name ?: 'Senza nome' }}</span>@endforeach
                        </a>
                    @endfor
                </div>
            </div>
            <div id="day-details" class="card" style="margin-top:24px;scroll-margin-top:20px">
                <h2>{{ \Carbon\Carbon::parse($selectedDate)->locale('it')->translatedFormat('l d F Y') }}</h2>
                <p class="capacity">Animali confermati: {{ $usedAnimals }}/5</p>
                @forelse($dayBookings as $booking)
                    <article class="booking {{ $booking->cancelled_at?'cancelled':($booking->confirmed_at?'':'pending') }}">
                        @if($booking->cancelled_at)
                            <div class="badge cancelled">Annullata</div>
                        @elseif(!$booking->confirmed_at)
                            <div class="badge">Da completare · richiesta dal sito</div>
                        @endif
                        <strong>{{ $booking->customer_name ?: 'Cliente senza nome' }}</strong><br>
                        {{ $booking->participants ?? '–' }} partecipanti · {{ $booking->animals ?? '–' }} animali
                        @if($booking->start_time || $booking->end_time)<br>Orario: {{ $booking->start_time ? substr($booking->start_time, 0, 5) : '–' }}–{{ $booking->end_time ? substr($booking->end_time, 0, 5) : '–' }}@endif
                        @if($booking->phone)<br>{{ $booking->phone }}@endif @if($booking->email) · {{ $booking->email }}@endif
                        @if($booking->cancelled_at)<br><strong>Motivo:</strong> {{ $booking->cancellation_reason }}@if($booking->cancellation_reason_other) — {{ $booking->cancellation_reason_other }}@endif @endif
                        <div class="booking-actions">
                            @if(!$booking->confirmed_at && !$booking->cancelled_at)
                                <button class="action-button action-confirm modal-trigger" type="button" data-dialog-open="confirm-{{ $booking->id }}">Conferma prenotazione</button>
                            @endif
                            <button class="action-button action-edit modal-trigger" type="button" data-dialog-open="edit-{{ $booking->id }}">Modifica prenotazione</button>
                            @if(!$booking->cancelled_at)
                                <button class="action-button action-cancel modal-trigger" type="button" data-dialog-open="cancel-{{ $booking->id }}">Annulla prenotazione</button>
                            @endif
                        </div>
                        <dialog id="edit-{{ $booking->id }}" class="booking-dialog">
                            <div class="dialog-inner">
                                <div class="dialog-top"><h3>Modifica prenotazione</h3><button class="dialog-close" type="button" data-dialog-close aria-label="Chiudi">×</button></div>
                                <form class="dialog-edit-form" method="post" action="{{ route('agenda.update',$booking) }}">
                                    @csrf @method('PUT')
                                    <label>Giorno</label><input type="date" name="booking_date" value="{{ $booking->booking_date?->toDateString() }}" required>
                                    <div class="two"><div><label>Dalle ore</label><input type="time" name="start_time" value="{{ $booking->start_time ? substr($booking->start_time,0,5) : '' }}"></div><div><label>Alle ore</label><input type="time" name="end_time" value="{{ $booking->end_time ? substr($booking->end_time,0,5) : '' }}"></div></div>
                                    <div class="two"><div><label>Numero partecipanti</label><input type="number" min="0" name="participants" value="{{ $booking->participants }}"></div><div><label>Numero animali</label><input type="number" min="0" max="5" name="animals" value="{{ $booking->animals }}"></div></div>
                                    <label>Nome e cognome del cliente</label><input name="customer_name" value="{{ $booking->customer_name }}">
                                    <label>Numero di telefono</label><input type="tel" name="phone" value="{{ $booking->phone }}">
                                    <label>Indirizzo email</label><input type="email" name="email" value="{{ $booking->email }}">
                                    <label>Fonte</label><select name="source" class="dialog-source"><option value="">Seleziona</option>@foreach($sources as $source)<option @selected(($booking->source ?: ($booking->origin==='website'?'Sito web':null))===$source)>{{ $source }}</option>@endforeach</select>
                                    <div class="dialog-source-other"><label>Specifica altra fonte</label><input name="source_other" value="{{ $booking->source_other }}"></div>
                                    <label>Note</label><textarea name="notes">{{ $booking->notes }}</textarea>
                                    <div class="dialog-buttons"><button class="dialog-back" type="button" data-dialog-close>Indietro</button><button class="dialog-edit" type="submit">Salva modifica</button></div>
                                </form>
                            </div>
                        </dialog>
                        @if(!$booking->confirmed_at && !$booking->cancelled_at)
                            <dialog id="confirm-{{ $booking->id }}" class="booking-dialog">
                                <div class="dialog-inner">
                                    <div class="dialog-top"><h3>Conferma prenotazione</h3><button class="dialog-close" type="button" data-dialog-close aria-label="Chiudi">×</button></div>
                                    <div class="dialog-booking"><strong>{{ $booking->customer_name ?: 'Cliente senza nome' }}</strong><br>{{ $booking->participants ?? '–' }} partecipanti · {{ $booking->animals ?? '–' }} animali<br>{{ $booking->booking_date?->format('d/m/Y') }}@if($booking->start_time || $booking->end_time) · {{ $booking->start_time ? substr($booking->start_time,0,5) : '–' }}–{{ $booking->end_time ? substr($booking->end_time,0,5) : '–' }}@endif</div>
                                    <p>Vuoi confermare questa prenotazione?</p>
                                    <form method="post" action="{{ route('agenda.confirm',$booking) }}">@csrf<div class="dialog-buttons"><button class="dialog-back" type="button" data-dialog-close>Indietro</button><button class="dialog-confirm" type="submit">Conferma prenotazione</button></div></form>
                                </div>
                            </dialog>
                        @endif
                        @if(!$booking->cancelled_at)
                            <dialog id="cancel-{{ $booking->id }}" class="booking-dialog">
                                <div class="dialog-inner">
                                    <div class="dialog-top"><h3>Annulla prenotazione</h3><button class="dialog-close" type="button" data-dialog-close aria-label="Chiudi">×</button></div>
                                    <div class="dialog-booking"><strong>{{ $booking->customer_name ?: 'Cliente senza nome' }}</strong><br>{{ $booking->participants ?? '–' }} partecipanti · {{ $booking->animals ?? '–' }} animali<br>{{ $booking->booking_date?->format('d/m/Y') }}@if($booking->start_time || $booking->end_time) · {{ $booking->start_time ? substr($booking->start_time,0,5) : '–' }}–{{ $booking->end_time ? substr($booking->end_time,0,5) : '–' }}@endif</div>
                                    <form class="cancellation-form" method="post" action="{{ route('agenda.cancel',$booking) }}">
                                        @csrf<label>Motivo annullamento</label><select name="cancellation_reason" class="cancellation-reason" required><option value="">Seleziona</option>@foreach($cancellationReasons as $reason)<option>{{ $reason }}</option>@endforeach</select>
                                        <div class="cancellation-other" hidden><label>Specifica il motivo</label><input name="cancellation_reason_other"></div>
                                        <div class="dialog-buttons"><button class="dialog-back" type="button" data-dialog-close>Indietro</button><button class="dialog-cancel" type="submit">Conferma annullamento</button></div>
                                    </form>
                                </div>
                            </dialog>
                        @endif
                    </article>
                @empty<p class="empty">Nessuna prenotazione per questo giorno.</p>@endforelse
            </div>
        </section>
        <aside class="card">
            <h2>{{ $formBooking ? 'Modifica prenotazione' : 'Nuova prenotazione' }}</h2>
            @if($errors->any())<div class="errors">{{ $errors->first() }}</div>@endif
            <form method="post" action="{{ $formBooking ? route('agenda.update',$formBooking) : route('agenda.store') }}">
                @csrf @if($formBooking) @method('PUT') @endif
                <label for="booking_date">Giorno</label><input id="booking_date" type="date" name="booking_date" value="{{ old('booking_date',$formBooking?->booking_date?->toDateString() ?? $selectedDate) }}" required>
                <div class="two"><div><label for="start_time">Dalle ore</label><input id="start_time" type="time" name="start_time" value="{{ old('start_time', $formBooking?->start_time ? substr($formBooking->start_time, 0, 5) : null) }}"></div><div><label for="end_time">Alle ore</label><input id="end_time" type="time" name="end_time" value="{{ old('end_time', $formBooking?->end_time ? substr($formBooking->end_time, 0, 5) : null) }}"></div></div>
                <div class="two"><div><label for="participants">Numero partecipanti</label><input id="participants" type="number" min="0" name="participants" value="{{ old('participants',$formBooking?->participants) }}"></div><div><label for="animals">Numero animali</label><input id="animals" type="number" min="0" max="5" name="animals" value="{{ old('animals',$formBooking?->animals) }}"></div></div>
                <label for="customer_name">Nome e cognome del cliente</label><input id="customer_name" name="customer_name" value="{{ old('customer_name',$formBooking?->customer_name) }}">
                <label for="phone">Numero di telefono</label><input id="phone" type="tel" name="phone" value="{{ old('phone',$formBooking?->phone) }}">
                <label for="email">Indirizzo email</label><input id="email" type="email" name="email" value="{{ old('email',$formBooking?->email) }}">
                @php($selectedSource = old('source', $formBooking?->source ?: ($formBooking?->origin === 'website' ? 'Sito web' : null)))
                <label for="source">Fonte</label><select id="source" name="source"><option value="">Seleziona</option>@foreach($sources as $source)<option @selected($selectedSource===$source)>{{ $source }}</option>@endforeach</select>
                <div id="source-other"><label for="source_other">Specifica altra fonte</label><input id="source_other" name="source_other" value="{{ old('source_other',$formBooking?->source_other) }}"></div>
                <label for="notes">Note</label><textarea id="notes" name="notes">{{ old('notes',$formBooking?->notes) }}</textarea>
                <button class="button" type="submit">{{ $formBooking && ($formBooking->confirmed_at || $formBooking->cancelled_at) ? 'Modifica prenotazione' : 'Conferma prenotazione' }}</button>
                @if($formBooking)<p><a href="{{ route('agenda.index',['date'=>$selectedDate,'month'=>$month->format('Y-m')]) }}">Annulla modifica</a></p>@endif
            </form>
        </aside>
    </div>
</main>
<script>const source=document.getElementById('source'),other=document.getElementById('source-other');function toggleOther(){other.hidden=source.value!=='Altro'}source.addEventListener('change',toggleOther);toggleOther();document.querySelectorAll('.cancellation-form').forEach(form=>{const reason=form.querySelector('.cancellation-reason'),otherReason=form.querySelector('.cancellation-other');reason.addEventListener('change',()=>otherReason.hidden=reason.value!=='Altro')});document.querySelectorAll('.dialog-edit-form').forEach(form=>{const select=form.querySelector('.dialog-source'),otherSource=form.querySelector('.dialog-source-other');const toggle=()=>otherSource.hidden=select.value!=='Altro';select.addEventListener('change',toggle);toggle()});document.querySelectorAll('[data-dialog-open]').forEach(button=>button.addEventListener('click',()=>document.getElementById(button.dataset.dialogOpen)?.showModal()));document.querySelectorAll('[data-dialog-close]').forEach(button=>button.addEventListener('click',()=>button.closest('dialog')?.close()));document.querySelectorAll('.booking-dialog').forEach(dialog=>dialog.addEventListener('click',event=>{if(event.target===dialog)dialog.close()}));</script>
</body></html>
