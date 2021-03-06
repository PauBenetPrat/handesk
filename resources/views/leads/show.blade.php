@extends('layouts.app')
@section('content')
    <div class="description comment">
        <a href="{{ route('leads.index') }}">Leads</a>
        <h3> {{ $lead->company }} · {{ $lead->name }} · {{ $lead->email }} </h3>
        @busy <span class="label lead-status-{{ $lead->statusName() }}"> {{ __("lead.".$lead->statusName() ) }} </span> &nbsp;
        <span class="date">{{  $lead->created_at->diffForHumans() }} · {{  nameOrDash($lead->team) }}</span>
        <a class="float-right button secondary mr4 mt5 mb-5" href="{{route('leads.tasks.index',$lead)}}"> @icon(tasks) {{ trans_choice('lead.task',2) }} <span class="label lead-status-failed">{{ $lead->uncompletedTasks->count() }}</span></a>
    </div>

    <div class="description comment">
        {{ Form::open(["url" => route('leads.update',$lead), 'method' => "PUT"]) }}
        <table>
            @include('components.lead.fields', ["lead" => $lead])
            <tr><td colspan="3"><button class="uppercase"> {{ __('ticket.update') }}</button></td></tr>
        </table>
        {{ Form::close() }}
    </div>
    @include('components.assignActions', ["endpoint" => "leads", "object" => $lead])

    <div class="comment new-comment">
        {{ Form::open(["url" => route("leads.status.store",$lead), "files" => true]) }}
        <textarea name="body"></textarea>
        <br>
        @include('components.uploadAttachment', ["attachable" => $lead, "type" => "leads"])
        {{ Form::select("new_status", App\Lead::availableStatus(), $lead->status) }}
        <button class="uppercase ph3 ml1"> @icon(comment) {{ __('ticket.comment') }}</button>
        {{ Form::close() }}
    </div>
    @include('components.leadStatus')
@endsection

@section('scripts')
    @include('components.js.taggableInput', ["el" => "tags", "endpoint" => "leads", "object" => $lead])
@endsection