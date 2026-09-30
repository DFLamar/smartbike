{{-- Badge de statut d'une commande. Utilisation : @include('partials.statut', ['statut' => $commande->statut]) --}}
<span class="badge statut-{{ $statut }}">
    @if($statut === 'en_attente') En attente
    @elseif($statut === 'payee') Payée
    @elseif($statut === 'expediee') Expédiée
    @else Annulée
    @endif
</span>
