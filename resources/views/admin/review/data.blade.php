<table class="table table-striped m-b-0" id="review-table">
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Name</th>
            <th>Role</th>
            <th>Rating</th>
            <th>Remark</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

        @forelse($reviewData as $review)
        {{-- {{dd(count($review->getReviewData))}} --}}
            {{-- @if (count($review->getReviewData) > 0) --}}
                <tr>
                    <td>
                        <div class="inline">{{ ((($reviewData->currentPage() - 1 ) * $reviewData->perPage() ) + $loop->iteration) . '.' }}
                        </div>
                    </td>
                    <td class="review-patient-name">{{$review->name}}</td>
                    <td>
                        @foreach($review->getReviewData as $row)
                            <div class="review-role-name">{{$row->getReviewRole->name}}</div>
                        @endforeach
                    </td>
                    <td>
                        @foreach($review->getReviewData as $row)
                            @for($i = 0; $i < 5; ++$i)
                                <i class="zmdi zmdi-star{{ $row->rate <= $i ? '-o' : '' }}" aria-hidden="true" style="color:#eca63b"></i>
                            @endfor
                            @php
                                echo '<br />';
                            @endphp
                        @endforeach
                    </td>
                    <td>
                        @foreach($review->getReviewData as $row)
                            <span>{{$row->remark}}</span>
                            @php
                                echo '<br />';
                            @endphp
                        @endforeach
                    </td>
                    <td>
                        <a class="">
                            <button class="btn btn-icon btn-neutral candor-color btn-icon-mini review-delete" data-id="{{$review->id}}">
                                <i class="zmdi zmdi-delete material-icons"></i>
                            </button>
                        </a>

                    </td>
                </tr>

        @empty
            <td colspan="5" class="text-center">No records available</td>
        @endforelse
    </tbody>
</table>

{{$reviewData->links()}}