<div>
    <div class="controls">
        <div class="pager-info">Showing {{ $movies->firstItem() }} to {{ $movies->lastItem() }} of {{ $movies->total() }}</div>
        <select wire:model="perPage" class="select">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
        </select>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Release Date</th>
                    <th>Popularity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movies as $movie)
                    <tr>
                        <td>{{ $movie->id }}</td>
                        <td>{{ $movie->title_en }}</td>
                        <td>{{ $movie->release_date }}</td>
                        <td>{{ $movie->popularity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pager">
        <button class="btn" wire:click="previousPage" @if ($movies->onFirstPage()) disabled @endif>Previous</button>
        <div class="pager-info">Page {{ $movies->currentPage() }} of {{ $movies->lastPage() }}</div>
        <button class="btn" wire:click="nextPage" @if (!$movies->hasMorePages()) disabled @endif>Next</button>
    </div>
</div>
