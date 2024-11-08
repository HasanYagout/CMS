@extends('layouts.app_course')

@section('content')
    <x-wrapper title="">

        @if($Announcements->isEmpty())
            <p>No announcements available.</p>
        @else
            @foreach($Announcements as $index => $announcement)
                <section
                    class="announcement bg-light-blue flex-column gap-3 justify-content-between mb-3 p-20 rounded d-flex">
                    <h3 class="text-black">{{ $announcement->title }}</h3>
                    @if($announcement->announcement_type == 'vote')
                        @php
                            $choices = json_decode($announcement->choices, true);
                            $studentVote = \App\Models\Vote::where('announcement_id', $announcement->id)
                                                           ->where('student_id', Auth::id())
                                                           ->first();
                        @endphp
                        <form action="{{ route('student.courses.announcements.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="announcement_id" value="{{ $announcement->id }}">
                            <div>
                                @foreach($choices as $choice)
                                    <div>
                                        <input type="radio" name="choice" value="{{ $choice['name'] }}"
                                            {{ $studentVote && $studentVote->choice == $choice['name'] ? 'checked' : '' }}>
                                        {{ $choice['name'] }}
                                    </div>
                                @endforeach
                            </div>
                            <button type="submit" class="zBtn-two">Vote</button>
                        </form>
                        <div>
                            <h4 class="text-black">Results:</h4>
                            @foreach($choices as $choice)
                                @php
                                    $totalVotes = array_sum(array_column($choices, 'count'));
                                    $percentage = $totalVotes > 0 ? ($choice['count'] / $totalVotes) * 100 : 0;
                                @endphp
                                <div>{{ $choice['name'] }}: {{ round($percentage, 2) }}% ({{ $choice['count'] }}
                                    votes)
                                </div>
                            @endforeach
                        </div>

                    @else
                        <span>{{ $announcement->text }}</span>

                    @endif
                    <span class="text-third-color">{{ $announcement->created_at }}</span>
                </section>
            @endforeach
        @endif
    </x-wrapper>
@endsection
