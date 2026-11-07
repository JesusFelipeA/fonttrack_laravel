{{-- Paginador de FontTrack (sustituye a pagination::bootstrap-5, en español y accesible). --}}
@if ($paginator->hasPages())
    <nav class="d-flex flex-wrap align-items-center justify-content-between gap-2 w-100" aria-label="Paginación">
        <p class="small text-body-secondary mb-0 ft-num">
            Mostrando <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
            a <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
            de <span class="fw-semibold">{{ $paginator->total() }}</span> resultados
        </p>

        <ul class="pagination mb-0">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link"><i class="bi bi-chevron-left" aria-hidden="true"></i><span class="visually-hidden">Página anterior</span></span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Página anterior"><i class="bi bi-chevron-left" aria-hidden="true"></i></a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}" aria-label="Página {{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Página siguiente"><i class="bi bi-chevron-right" aria-hidden="true"></i></a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link"><i class="bi bi-chevron-right" aria-hidden="true"></i><span class="visually-hidden">Página siguiente</span></span>
                </li>
            @endif
        </ul>
    </nav>
@endif
