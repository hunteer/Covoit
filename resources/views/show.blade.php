@extends('template')

@section('contenu')
    <div class="col-sm-offset-4 col-sm-4">
    	<br>
		<div class="panel panel-primary">	
			<div class="panel-heading">Fiche d'utilisateur</div>
			<div class="panel-body"> 
				<p>Nom : {{ $user->name }}</p>
				<p>Prénom : {{ $user->prenomUsers }}</p>
				<p>Pseudo : {{ $user->pseudoUsers }}</p>
				<p>Email : {{ $user->email }}</p>
				<p>Date de naissance : {{ $user->dateNaissanceUsers }}</p>
				
				<?php if($user->telPortUsers !=''){ ?>
					<p>Numérode tel. portable : {{ $user->telPortUsers }}</p>				
				<?php } ?>
				
				<?php if($user->telFixeUsers !=''){ ?>
					<p>Numérode tel. fixe : {{ $user->telFixeUsers }}</p>				
				<?php } ?>
				
				@if($user->admin == 1)
					Administrateur
				@endif
			</div>
		</div>				
		<a href="javascript:history.back()" class="btn btn-primary">
			<span class="glyphicon glyphicon-circle-arrow-left"></span> Retour
		</a>
	</div>
@stop