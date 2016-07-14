<?php $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>

<ul class="nav navbar-nav navbar-left" role="navigation">

    <li class="<?php echo $url == route('sanatorium.bill.bills.create') ? 'active' : '' ; ?> ">

        <a target="_self" href="{{ route('sanatorium.bill.bills.create') }}">
            Make a bill
        </a>

    </li>

    <li class="<?php echo $url == route('sanatorium.bill.bills.index') ? 'active' : '' ; ?>">

        <a target="_self" href="{{ route('sanatorium.bill.bills.index') }}">
            Bills
        </a>

    </li>

    <li class="<?php echo $url == route('sanatorium.clients.clients.index') ? 'active' : '' ; ?>">

        <a target="_self" href="{{ route('sanatorium.clients.clients.index') }}">
            Subjects
        </a>

    </li>

    <li class="<?php echo $url == route('sanatorium.clients.clients.new') ? 'active' : '' ; ?>">
        
        <a target="_self" href="{{ route('sanatorium.clients.clients.new') }}">
            
            Make a subject

        </a>

    </li>
</ul>