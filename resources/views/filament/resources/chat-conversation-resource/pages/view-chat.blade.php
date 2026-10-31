<x-filament-panels::page>
    <div wire:poll.4s>
        <div x-data x-init="$el.scrollTop = $el.scrollHeight"
             style="max-height:60vh; overflow-y:auto; padding:1rem; border:1px solid rgba(168,85,247,.4); border-radius:.75rem; background:rgba(6,4,17,.7); display:flex; flex-direction:column; gap:.5rem;">
            @forelse ($messages as $m)
                <div wire:key="msg-{{ $m->id }}"
                     style="display:flex; justify-content: {{ $m->sender === 'admin' ? 'flex-end' : 'flex-start' }};">
                    <div style="max-width:75%; padding:.5rem .8rem; border-radius:.75rem; white-space:pre-wrap; word-break:break-word; color:#f1f5f9; background: {{ $m->sender === 'admin' ? 'rgba(6,182,212,.25)' : 'rgba(168,85,247,.25)' }};">
                        <div style="font-size:.65rem; opacity:.7; font-family:monospace;">
                            {{ $m->sender === 'admin' ? 'Kamu' : $this->record->visitor_name }}
                            · {{ $m->created_at->timezone('Asia/Jakarta')->format('d M H:i') }}
                        </div>
                        {{ $m->body }}
                    </div>
                </div>
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
