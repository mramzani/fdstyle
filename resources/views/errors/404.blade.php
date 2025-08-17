@extends('errors.errorLayout')

@section('title',__('error-page.Not Found'))
@section('message',__('error-page.Page Not Found'))
@section('description',__('error-page.The page you were looking for was not found!'))
@section('btnText',__('error-page.Go To Home Page'))
@section('lightImg',asset('illustrations/page-misc-error-dark.png'))
@section('lightImg','illustrations/page-misc-error-light.png')
@section('darkImg','illustrations/page-misc-error-dark.png')

