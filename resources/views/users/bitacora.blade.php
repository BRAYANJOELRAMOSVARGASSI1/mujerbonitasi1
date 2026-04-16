@extends('layouts.app')

@section('content')
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
            <h5 style="margin:0; font-weight:600;">{{ __('Mis registros') }}</h5>
        </div>

        <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
            <!-- Tabla -->
            <div class="table-responsive"
                style="background-color:white; border-radius:12px; padding:1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                <table class="table align-middle" style="margin:0;">
                    <thead>
                        <tr style="border-bottom:2px solid #CC5CB8;">
                            <th scope="col" style="color:#CC5CB8; font-weight:600; padding:1rem 0.75rem; border:none;">
                                Usuario
                            </th>
                            <th scope="col" style="color:#CC5CB8; font-weight:600; padding:1rem 0.75rem; border:none;">
                                Dirección IP
                            </th>
                            <th scope="col" style="color:#CC5CB8; font-weight:600; padding:1rem 0.75rem; border:none;">
                                Navegador
                            </th>
                            <th scope="col" style="color:#CC5CB8; font-weight:600; padding:1rem 0.75rem; border:none;">
                                Acción
                            </th>
                            <th scope="col" style="color:#CC5CB8; font-weight:600; padding:1rem 0.75rem; border:none;">
                                Descripción
                            </th>
                            <th scope="col" style="color:#CC5CB8; font-weight:600; padding:1rem 0.75rem; border:none;">
                                Fecha
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activityLogs as $log)
                            <tr style="border-bottom:1px solid #f1f3f4; transition:background-color 0.2s;"
                                onmouseover="this.style.backgroundColor='#f8f9fa'"
                                onmouseout="this.style.backgroundColor='transparent'">
                                <td style="padding:1rem 0.75rem; border:none;">
                                    <span class="badge"
                                        style="background-color:#CC5CB8; color:white; font-size:0.8rem; padding:0.375rem 0.75rem; border-radius:20px; font-weight:500;">
                                        {{ $log->user?->name ?? 'Desconocido' }}
                                    </span>
                                </td>
                                <td style="padding:1rem 0.75rem; border:none;">
                                    <span class="badge"
                                        style="background-color:#CC5CB8; color:white; font-size:0.8rem; padding:0.375rem 0.75rem; border-radius:20px; font-weight:500;">
                                        {{ $log->ip_address }}
                                    </span>
                                </td>
                                <td style="padding:1rem 0.75rem; border:none;">
                                    <span class="badge"
                                        style="background-color:#CC5CB8; color:white; font-size:0.8rem; padding:0.375rem 0.75rem; border-radius:20px; font-weight:500;">
                                        {{ $log->browser }}
                                    </span>
                                </td>
                                <td style="padding:1rem 0.75rem; border:none; color:#212529; font-weight:500;">
                                    <div style="display:flex; align-items:center;">
                                        <div
                                            style="width:35px; height:35px; background-color:#CC5CB8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:0.75rem;">
                                            <svg class="icon" style="color:white; width:16px; height:16px;">
                                                <use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use>
                                            </svg>
                                        </div>
                                        {{ $log->user?->name ?? 'Desconocido' }}
                                    </div>
                                </td>
                                <td style="padding:1rem 0.75rem; border:none;">
                                    @if(str_contains($log->action, 'ALERTA') || str_contains(strtolower($log->action), 'bloqueo'))
                                    <span class="badge"
                                        style="background-color:#dc3545; color:white; font-size:0.8rem; padding:0.375rem 0.75rem; border-radius:20px; font-weight:700; box-shadow: 0 0 10px rgba(220,53,69,0.4);">
                                        {{ $log->action }}
                                    </span>
                                    @else
                                    <span class="badge"
                                        style="background-color:#CC5CB8; color:white; font-size:0.8rem; padding:0.375rem 0.75rem; border-radius:20px; font-weight:500;">
                                        {{ $log->action }}
                                    </span>
                                    @endif
                                </td>
                                <td
                                    style="white-space: pre-wrap; padding:1rem 0.75rem; border:none; color:#495057; font-size:0.9rem; line-height:1.4;">
                                    {{ $log->description }}</td>
                                <td style="padding:1rem 0.75rem; border:none; color:#6c757d; font-size:0.85rem;">
                                    <div style="display:flex; align-items:center;">
                                        <svg class="icon me-2" style="color:#CC5CB8; width:14px; height:14px;">
                                            <use xlink:href="{{ asset('icons/coreui.svg#cil-calendar') }}"></use>
                                        </svg>
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center" style="padding:3rem; color:#6c757d; border:none;">
                                    <div style="display:flex; flex-direction:column; align-items:center;">
                                        <svg class="icon mb-3" style="color:#CC5CB8; width:48px; height:48px;">
                                            <use xlink:href="{{ asset('icons/coreui.svg#cil-clipboard') }}"></use>
                                        </svg>
                                        <p style="margin:0; font-size:1.1rem;">No hay registros en la bitácora.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        <div class="card-footer"
            style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
            <style>
                .pagination .page-link {
                    color: #662a5b;
                    border-color: #662a5b;
                    background-color: white;
                    border-radius: 8px;
                    margin: 0 2px;
                    padding: 0.5rem 0.75rem;
                    font-weight: 500;
                    transition: all 0.2s;
                }

                .pagination .page-link:hover {
                    color: white;
                    background-color: #662a5b;
                    border-color: #662a5b;
                    transform: translateY(-1px);
                }

                .pagination .page-item.active .page-link {
                    color: white;
                    background-color: #662a5b;
                    border-color: #662a5b;
                    box-shadow: 0 2px 8px rgba(102, 42, 91, 0.3);
                }

                .pagination .page-item.disabled .page-link {
                    color: #6c757d;
                    background-color: #f8f9fa;
                    border-color: #dee2e6;
                }
            </style>
            {{ $activityLogs->links() }}
        </div>
    </div>
@endsection
