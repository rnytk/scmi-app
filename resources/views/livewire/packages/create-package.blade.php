<div>


    <flux:card>
        <div class="max-w-4xl mx-auto py-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <flux:heading size="xl">Registrar Nuevo Envío</flux:heading>
                    <flux:subheading>Complete la información para generar el código de rastreo.</flux:subheading>
                </div>
                <flux:button :href="route('packages.index')" variant="ghost" icon="arrow-left">
                    Volver al listado
                </flux:button>
            </div>

            <flux:card>
                <form wire:submit="save" class="space-y-6">

                    <fieldset class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:select wire:model="package_type_id" label="Tipo de Paquete"
                            placeholder="Seleccione una opción...">
                            @foreach ($packageTypes as $type)
                                <flux:select.option value="{{ $type->id }}">{{ $type->name }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:select wire:model="assigned_messenger_id" label="Mensajero Asignado" placeholder="Buscar mensajero..." searchable>
                            @foreach($messengers as $messenger)
                                <flux:select.option value="{{ $messenger->id }}">{{ $messenger->name }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:input wire:model="description" label="Descripción / Referencia"
                            placeholder="Ej. Documentos legales" />
                    </fieldset>

                    <flux:separator variant="subtle" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <flux:heading size="lg">Datos de Origen</flux:heading>


                            <flux:input label="Agencia Remitente"
                                value="{{ auth()->user()->agency->name ?? 'Agencia Central' }}" disabled />

                            <flux:input label="Colaborador Remitente" value="{{ auth()->user()->name }}" disabled />

                        </div>

                        <div class="space-y-4">
                            <flux:heading size="lg">Datos de Destino</flux:heading>

                            <flux:select wire:model.live="destination_agency_id" label="Agencia Destino"
                                placeholder="Seleccione destino..." searchable>
                                @foreach ($agencies as $agency)
                                    <flux:select.option value="{{ $agency->id }}">{{ $agency->name }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>

                            <flux:select wire:model="recipient_id" label="Colaborador Destinatario"
                                placeholder="Seleccione colaborador..." searchable>
                                @foreach ($users as $user)
                                    <flux:select.option value="{{ $user->id }}">{{ $user->name }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <flux:button type="submit" variant="primary">
                            Registrar Envío
                        </flux:button>
                    </div>
                </form>
            </flux:card>
        </div>
    </flux:card>
</div>
