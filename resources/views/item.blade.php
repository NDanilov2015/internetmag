@extends('layouts.front')

@section('title', 'Item page!')

@section('content')
	@include('item-showcontent', ['needSimilarItemsShow' => true]);
@endsection