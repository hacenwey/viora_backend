<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header bg-white p-4">
                <h2 class="mb-0">{{ $survey->name }}</h2>

                @if(!$eligible)
                    We only accept
                    <strong>{{ $survey->limitPerParticipant() }} {{ \Str::plural('entry', $survey->limitPerParticipant()) }}</strong>
                    per participant.
                @endif

                @if($lastEntry)
                    You last submitted your answers <strong>{{ $lastEntry->created_at->diffForHumans() }}</strong>.
                @endif
            </div>
            @if(!$survey->acceptsGuestEntries() && auth()->guest())
                <div class="p-5">
                    Please login to join this survey.
                </div>
            @else
                <form method="POST" action="{{ route("backend.surveys.new-entries", ['survey' => $survey]) }}" enctype="multipart/form-data">
                    @csrf
                    @foreach($survey->sections as $section)
                        @include('survey::sections.single')
                    @endforeach

                    @foreach($survey->questions()->withoutSection()->get()->sortByDesc('id') as $question)
                        @include('survey::questions.single')
                    @endforeach

                    @if($eligible)
                        <button class="btn btn-primary btn-block">Submit</button>
                    @endif
                </form>
            @endif
        </div>
    </div>
</div>
