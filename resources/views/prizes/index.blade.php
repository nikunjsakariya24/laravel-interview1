@extends('default')

@section('content')

@include('prob-notice')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h1>Prizes</h1>
                <a href="{{ route('prizes.create') }}" class="btn btn-outline-info align-content-center">Create</a>
            </div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Probability</th>
                        <th>Awarded</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prizes as $prize)
                    <tr>
                        <td>{{ $prize->id }}</td>
                        <td>{{ $prize->title }}</td>
                        <td>{{ $prize->probability }}</td>
                        <td>{{ $prize->winner_count }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('prizes.edit', [$prize->id]) }}" class="btn btn-primary">Edit</a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['prizes.destroy', $prize->id]]) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Simulate</h3>
                    <p>prizes - {{ $prizes->sum('winner_count') }}</p>
                </div>
                <div class="card-body">
                    {!! Form::open(['method' => 'POST', 'route' => 'simulate']) !!}
                    <div class="form-group">
                        {!! Form::label('number_of_prizes', 'Number of Prizes') !!}
                        {!! Form::number('number_of_prizes', 50, ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::submit('Simulate', ['class' => ['btn btn-primary', 'form-control', 'mt-2']]) !!}
                    {!! Form::close() !!}
                </div>
                <div class="card-footer">
                    {!! Form::open(['method' => 'POST', 'route' => 'reset']) !!}
                    {!! Form::submit('Reset', ['class' => ['btn btn-outline-secondary', 'form-control']]) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container m-4">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6 text-center">
            <h2>Probability Settings</h2>
            <canvas id="probabilityChart"></canvas>
        </div>
        <div class="col-md-6 text-center">
            <h2>Actual Rewards</h2>
            <canvas id="actualProbabilityChart"></canvas>
        </div>
    </div>
</div>


@stop


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    var probabilityData = @json($prizes->pluck('probability'));
    var awardedData = @json($prizes->pluck('winner_count'));
    var prizeNames = @json($prizes->pluck('title'));

    var probabilityDataLabel = prizeNames.map(function(name, index) {
        return name + ': ' + probabilityData[index] + '%';
    });

    var probabilityChartCanvas = document.getElementById('probabilityChart');
    var probabilityChart = new Chart(probabilityChartCanvas, {
        type: 'doughnut',
        data: {
            labels: probabilityDataLabel,
            datasets: [{
                data: probabilityData
            }]
        }
    });

    var totalDistributed = awardedData.reduce(function(total, count) {
        return total + count;
    }, 0);

    var actualProbabilityData = awardedData.map(function(count) {
        if (totalDistributed !== 0) {
            return (count / totalDistributed * 100).toFixed(2);
        } else {
            return 0;
        }
    });

    var actualProbabilityDataLabel = prizeNames.map(function(name, index) {
        return name + ': ' + actualProbabilityData[index] + '%';
    });

    var actualProbabilityChartCanvas = document.getElementById('actualProbabilityChart');
    var actualProbabilityChart = new Chart(actualProbabilityChartCanvas, {
        type: 'doughnut',
        data: {
            labels: actualProbabilityDataLabel,
            datasets: [{
                data: actualProbabilityData
            }]
        }
    });
</script>
@endpush
