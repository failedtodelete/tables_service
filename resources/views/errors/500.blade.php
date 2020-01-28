@extends('errors.minimal')

{{--@section('title', __('Ошибка сервера'))--}}
@section('code', '500')
@section('message', __($exception->getMessage() ?: 'Ошибка сервера'))
