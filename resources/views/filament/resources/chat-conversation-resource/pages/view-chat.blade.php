<x-filament-panels::page>
    <div wire:poll.4s>
        <div x-data x-init="$el.scrollTop = $el.scrollHeight"
             style="max-height:65vh; overflow-y:auto; padding:1rem; border:1px solid rgba(168,85,247,.4); border-radius:.75rem; background:rgba(6,4,17,.7); display:flex; flex-direction:column; gap:.5rem;">
            @forelse ($messages as $m)
                @php($mine = $m->sender === 'admin')
                <div wire:key="msg-{{ $m->id }}" style="display:flex; flex-shrink:0; justify-content: {{ $mine ? 'flex-end' : 'flex-start' }};"><div style="width:fit-content; max-width:min(70%, 28rem); padding:.45rem .7rem .5rem; border-radius:.75rem; text-align:left; color:#f1f5f9; background: {{ $mine ? 'rgba(6,182,212,.28)' : 'rgba(168,85,247,.3)' }};"><div style="display:flex; gap:.6rem; align-items:baseline; justify-content:space-between; margin-bottom:.15rem; font-family:monospace; font-size:.65rem; line-height:1.2;"><span style="font-weight:700; color: {{ $mine ? '#67e8f9' : '#c4b5fd' }};">{{ $mine ? 'Kamu' : $this->record->visitor_name }}</span><span style="opacity:.6;">{{ $m->created_at->timezone('Asia/Jakarta')->format('d M H:i') }}</span></div><div style="white-space:pre-wrap; word-break:break-word; font-size:.9rem; line-height:1.35;">{{ $m->body }}</div></div></div>
            @empty
                <div style="color:#94a3b8; font-family:monospace; font-size:.8rem;">Belum ada pesan.</div>
            @endforelse
        </div>
    </div>

    <form wire:submit="send" style="display:flex; gap:.5rem; margin-top:1rem;">
        <div style="flex:1;">
            <x-filament::input.wrapper>
                <x-filament::input type="text" wire:model="reply" placeholder="Tulis balasan..." />
            </x-filament::input.wrapper>
        </div>
        <x-filament::button type="submit">Kirim</x-filament::button>
    </form>
</x-filament-panels::page>
