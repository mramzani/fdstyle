@extends('errors.errorLayout')

@section('title',__('error-page.Not Authorized'))
@section('message',__('error-page.You are not authorized!'))
@section('description',__('error-page.You do not have permission to access this page.'))
@section('btnText',__('error-page.Go To Home Page'))
@section('lightImg',asset('illustrations/girl-hacking-site-dark.png'))
@section('lightImg','illustrations/girl-hacking-site-light.png')
@section('darkImg','illustrations/girl-hacking-site-dark.png')

