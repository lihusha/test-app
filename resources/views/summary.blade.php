@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <span>League table</span>
                        <div class="float-right">
                            <form action="{{ route('service.reset-schedule') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="btn btn-danger"
                                        onclick="return confirm('You want to reset all results. Are you sure?')">Reset schedule</button>
                            </form>
                        </div>
                        <div class="float-right mr-4">
                            {{ $summary->links('vendor.pagination.simple-bootstrap-4') }}
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @isset($summary)
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col"><small>Club</small></th>
                                    <th scope="col"><small>Played</small></th>
                                    <th scope="col"><small>Won</small></th>
                                    <th scope="col"><small>Drawn</small></th>
                                    <th scope="col"><small>Lost</small></th>
                                    <th scope="col"><small>GF</small></th>
                                    <th scope="col"><small>GA</small></th>
                                    <th scope="col"><small>GD</small></th>
                                    <th scope="col"><small>Points</small></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($summary as $item)
                                    <tr>
                                        <th scope="row">{{ $loop->index + 1 }}</th>
                                        <td>
                                            <img src="{{ data_get($item, 'thumbnail_path') }}" class="img-fluid" alt="{{ data_get($item, 'name') }}">
                                            <small>{{ data_get($item, 'name') }}</small>
                                        </td>
                                        <td class="text-center">{{ data_get($item, 'played') }}</td>
                                        <td class="text-center">{{ data_get($item, 'won') }}</td>
                                        <td class="text-center">{{ data_get($item, 'drawn') }}</td>
                                        <td class="text-center">{{ data_get($item, 'lost') }}</td>
                                        <td class="text-center">{{ data_get($item, 'goals_for') }}</td>
                                        <td class="text-center">{{ data_get($item, 'goals_against') }}</td>
                                        <td class="text-center">{{ data_get($item, 'goal_difference') }}</td>
                                        <td class="text-center">{{ data_get($item, 'points') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endisset
                    </div>

                    <div class="card-footer">

                        <div class="float-left">
                            <a href="{{ route('play') }}"
                               class="btn btn-success {{ $hasGames ? '' : 'disabled' }}"
                               role="button">Play all</a>
                        </div>

                        <div class="float-right">
                            {{ $summary->links('vendor.pagination.simple-bootstrap-4') }}
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">{{ $formattedWeek }} Week results</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @forelse($weekData as $item)
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span class="d-block float-right ml-3">{{ data_get($item, 'host.goals') }}</span>
                                            <span class="text-nowrap text-nowrap d-inline-block text-truncate float-right"
                                                  style="max-width: 80px;"
                                                  data-toggle="tooltip"
                                                  title="{{ data_get($item, 'host.name') }}"
                                                  role="button">
                                                <small>{{ data_get($item, 'host.name') }}</small>
                                            </span>

                                        </div>
                                        <div class="col-md-6 text-left">
                                            <span class="d-inline-block float-left mr-3">{{ data_get($item, 'guest.goals') }}</span>
                                            <span class="float-left text-nowrap d-inline-block text-truncate"
                                                  style="max-width: 80px;"
                                                  data-toggle="tooltip"
                                                  title="{{ data_get($item, 'guest.name') }}"
                                                  role="button">
                                                <small>{{ data_get($item, 'guest.name') }}</small>
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                Not scheduled yet.
                            @endforelse
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
