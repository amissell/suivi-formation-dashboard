@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-card border border-border rounded-lg p-6 shadow-sm">
        <h2 class="text-foreground font-semibold">Card Title</h2>
        <p class="text-muted-foreground mt-2">Card description goes here.</p>
    </div>
    <div class="bg-card border border-border rounded-lg p-6 shadow-sm">
        <h2 class="text-foreground font-semibold">Another Card</h2>
        <p class="text-muted-foreground mt-2">Another description here.</p>
    </div>
</div>
@endsection
