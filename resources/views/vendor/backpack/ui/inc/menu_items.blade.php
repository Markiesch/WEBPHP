{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Users" icon="la la-question" :link="backpack_url('user')" />
<x-backpack::menu-item title="Roles" icon="la la-question" :link="backpack_url('role')" />
<x-backpack::menu-item title="Landing pages" icon="la la-question" :link="backpack_url('landing-page')" />
<x-backpack::menu-item title="Advertisements" icon="la la-question" :link="backpack_url('advertisement')" />


<!-- Include your custom CSS file to override colors -->
@basset('public/css/custom-styles.css')
