<ol class="breadcrumb">
    <li><a href="/admin"><i class="icon-dashboard"></i> Dashboard</a></li>
    
    <?php 
    $url = '';
    foreach(LRequest::segments() as $segment) {
        $url .= "/$segment";
        if($segment == 'admin')   continue; ?>
        <li class="active"><a href='{{$url}}'>{{ preg_match('/[0-9]/', substr($segment, -1)) ? ucfirst(substr($segment, 0, -1)) . ' ' . substr($segment, -1) : ucfirst($segment) }}</a> </li>
    <?php } ?>
    
</ol>