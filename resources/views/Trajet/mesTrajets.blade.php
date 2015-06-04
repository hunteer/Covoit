@extends('template')

@section('contenu')
    <div class="col-md-10 col-md-offset-1">
    	@if(session()->has('ok'))
			<div class="alert alert-success alert-dismissible">{!! session('ok') !!}</div>
		@endif
		<div class="panel panel-primary">	
			<div class="panel-heading">Espace mes trajets</div>
				<div class="panel-body">
					@if(Input::get('trajet_id')!==null)
						<?php
							DB::table('T_TRAJET')
								->where('id', Input::get('trajet_id'))
								->update(['statutTrajet' => 1]);
						?>
						<div class="alert alert-success" role="alert">
							<strong><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Le statut du trajet a bien été placé a effectué, les paiements sont validés.</strong>
						</div>
					@endif
					
					@if(Input::get('note')!=null && Input::get('trajet_id_appreciation') != null)
						<?php
							$note = Input::get('note');
							$trajet = Input::get('trajet_id_appreciation');
							
							$appreciations=App\Appreciation_trajet::all();
							$ever_rated=false;
							foreach($appreciations as $appreciation){
								if($appreciation->trajet_id==$trajet 
									&& $appreciation->user_id==Auth::user()->id){
									$ever_rated=true;
								}
							}
						?>
						@if(!$ever_rated)
								<?php
							DB::table('t_appreciation_trajet')->insert(
								['trajet_id' => $trajet, 'user_id' => Auth::user()->id, 'valeurAppreciation' => $note]
							);
								?>
						@endif
						<div class="alert alert-success" role="alert">
							<strong>Votre note a bien été enregistrée.</strong>
						</div>
					@endif	
					
					<?php $nbTrajets = Auth::user()->trajets->count(); ?>
					
					<h3><span class="glyphicon glyphicon-road" aria-hidden="true"></span> Conducteur</h3>
					
					@if($nbTrajets > 0)
						<p><br/>Voici les trajets dans lesquels vous êtes conducteur.</p>
						<table class="table">
						<thead>
							<tr>
								<th>Ville de départ</th>
								<th>Ville d'arrivée</th>
								<th>Date</th>
								<th>Heure de départ</th>
								<th>Nombre de places</th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						@foreach (Auth::user()->trajets as $trajet)
							<?php
								if($trajet->nbPlacesTrajet !=0){
									$nbPlaces = $trajet->nbPlacesTrajet;
								}else{
									$nbPlaces = "Complet";
								}
							?>
							<tr>
								<td class="text-primary"><strong>{!! $trajet->villeDepartTrajet !!}</strong></td>
								<td class="text-primary"><strong>{!! $trajet->villeArriveeTrajet !!}</strong></td>
								<td class="text-primary"><strong>{!! $trajet->dateDebutTrajet !!}</strong></td>
								<td class="text-primary"><strong>{!! $trajet->heureDepartTrajet !!}</strong></td>
								<td class="text-primary"><strong>{!!  $nbPlaces !!}</strong></td>
								<td>{!! link_to_route('trajet.show', 'Voir', [$trajet->id], ['class' => 'btn btn-success btn-block']) !!}</td>
								@if(!$trajet->statutTrajet)
									<td>
										{!! Form::open(array('method' => 'get') ) !!}
										{!! Form::hidden('trajet_id', $trajet->id) !!}
										{!! Form::button('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Effectué', ['class' => 'btn btn-warning btn-block', 'onclick' => 'return confirm(\'Voulez vous vraiment valider ce trajet ?\nLes paiements seront ensuite validés.\')', 'type'=>'submit)']) !!}
										{!! Form::close() !!}
									</td>
									<td>
										{!! Form::open(['method' => 'DELETE', 'route' => ['trajet.destroy', $trajet->id]]) !!}
										{!! Form::submit('Supprimer', ['class' => 'btn btn-danger btn-block', 'onclick' => 'return confirm(\'Vraiment supprimer ce trajet ?\')']) !!}
										{!! Form::close() !!}
									</td>
								@else
									<td>
										<strong>Trajet effectué</strong>
									</td>
								@endif
							</tr>
						@endforeach
						</tbody>
						</table>
					@else
						<div class="alert alert-warning" role="alert">
							<strong>Vous n'avez aucun trajet de prévu en tant que conducteur.</strong>
						</div>
					@endif
					
					<br/><h3><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Passager</h3>
					
					<?php $nbTrajets = Auth::user()->trajetsPassager->count(); ?>
					@if($nbTrajets > 0)
						<br/><p>Voici les trajets dans lesquels vous êtes passager.</p>
						<table class="table">
						<thead>
							<tr>
								<th>Ville de départ</th>
								<th>Ville d'arrivée</th>
								<th>Date</th>
								<th>Heure de départ</th>
								<th>Nombre de places</th>
								<th>Statut</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						@foreach (Auth::user()->trajetsPassager as $trajetPassager)
							<?php
								if($trajetPassager->nbPlacesTrajet !=0){
									$nbPlaces = $trajetPassager->nbPlacesTrajet;
								}else{
									$nbPlaces = "Complet";
								}
								
								if($trajetPassager->statutTrajet){
									$statut = "Effectué";
								}else{
									$statut = "Non effectué";
								}
							?>
							<tr>
								<td class="text-primary"><strong>{!! $trajetPassager->villeDepartTrajet !!}</strong></td>
								<td class="text-primary"><strong>{!! $trajetPassager->villeArriveeTrajet !!}</strong></td>
								<td class="text-primary"><strong>{!! $trajetPassager->dateDebutTrajet !!}</strong></td>
								<td class="text-primary"><strong>{!! $trajetPassager->heureDepartTrajet !!}</strong></td>
								<td class="text-primary"><strong>{!! $nbPlaces !!}</strong></td>
								<td class="text-primary"><strong>{!! $statut !!}</strong></td>
								<td>{!! link_to_route('trajet.show', 'Voir', [$trajetPassager->id], ['class' => 'btn btn-success btn-block']) !!}</td>
								@if($trajetPassager->statutTrajet)
									<?php
										$aNote=false;
										$appreciations=App\Appreciation_trajet::all();
										foreach($appreciations as $appreciation){
											$idUser =  Auth::user()->id;
											if(trim($appreciation->user_id) == trim($idUser) && trim($appreciation->trajet_id) == trim($trajetPassager->id)){
												$aNote=true;
												$note_trajet=$appreciation->valeurAppreciation;
											}
										}
									
									?>
									@if($aNote==false)
										<td>
													{!! Form::open(array('method' => 'post')) !!}
													{!! Form::selectRange('note', 0, 10, null, ['class' => 'form-control']) !!}
										</td><td><h4>/10</h4></td>
										<td>
													{!! Form::hidden('trajet_id_appreciation', $trajetPassager->id) !!}
													{!! Form::submit('Noter', ['class' => 'btn btn-warning btn-block']) !!}
													{!! Form::close() !!}
										</td>
									@else
										<td>
												<strong><h6><i>Vous l'avez noté {{ $note_trajet }}/10</i></h6></strong>
										</td>
									@endif
								@endif
							</tr>
						@endforeach
						</tbody>
						</table>
					@else
						<div class="alert alert-warning" role="alert">
							<strong>Vous n'avez aucun trajet de prévu en tant que passager.</strong>
						</div>
					@endif
				</div>
		</div>
		<a href="javascript:history.back()" class="btn btn-primary">
			<span class="glyphicon glyphicon-circle-arrow-left"></span> Retour
		</a>
	</div>
@stop