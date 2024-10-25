<h1>Main kia Welcome jiiiiiiiiiiiiiiiii</h1>
<li>
    <!-- Authentication -->
<form method="POST" action="{{ route('restaurant.logout') }}">
@csrf

<x-responsive-nav-link :href="route('restaurant.logout')"
onclick="event.preventDefault();
   this.closest('form').submit();">
{{ __('Log Out') }}
</x-responsive-nav-link>
</form>
</li>