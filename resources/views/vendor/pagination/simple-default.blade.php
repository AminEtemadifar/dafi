@if ($paginator->hasPages())
	<nav>
		<ul class="pagination">
			{{-- Previous Page Link --}}
			@if ($paginator->onFirstPage())
				<li class="disabled" aria-disabled="true" aria-label="« قبلی">
					<span class="page-link" aria-hidden="true">« قبلی</span>
				</li>
			@else
				<li>
					<a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="« قبلی">« قبلی</a>
				</li>
			@endif

			{{-- Next Page Link --}}
			@if ($paginator->hasMorePages())
				<li>
					<a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="بعدی »">بعدی »</a>
				</li>
			@else
				<li class="disabled" aria-disabled="true" aria-label="بعدی »">
					<span class="page-link" aria-hidden="true">بعدی »</span>
				</li>
			@endif
		</ul>
	</nav>
@endif
