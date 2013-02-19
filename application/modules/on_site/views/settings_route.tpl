<p class="zero" style="line-height: 25px;">

{t}Some companies providing on-site assistance or having representatives organize their working day in routes{/t}.<br/>

{t}A route is a set of cities, representing the path followed by a team member during his "on-site" working day{/t}.<br/>

{t}Every city is a node of the route and can be listed in only one route while a team member should be associated to one or more routes{/t}.<br/>

{t}When a task is created and a destination is set, if the city of the destination matches one of the routes then the team members associated to that route will be automatically involved in the task{/t}.

</p>
<hr/>

<div id="routes_list" style="margin-top: 20px;">
	
	<p class="zero" style="font-weight: bold; margin-bottom: 20px;">{t}Nodes found{/t}: {$routes|count}</p>
	
	{if ($routes|count) > 0}

		{assign var="previous_route" value=""}
		{assign var="k" value=1}
		
		{foreach $routes as $route}
		
			{if $route.route_name != $previous_route}
			
				{if $k!=1}</ul></div>{/if}
				
				<h4>{$route.route_name}</h4>
				
				<div class="box" style="line-height: 25px; margin-bottom: 20px; padding: 5px;">
				<ul style="list-style: none;">
				
				{$previous_route = $route.route_name }
			{/if}
			
			<li class="zero" style="margin-right: 5px; display: inline-block;">
				{$route.city|trim}
				<a class="zero" id="button_delete_node" href="#" onClick="jquery_Noform_Post_Reload({ 'object_id':{$route.id},'hash':'set_here_the_hash' },'/on_site/ajax/delete_route')"><img style="margin-left: -5px;" src="/layout/images/error.png" /></a>
			</li>
			
			{$k = $k+1}	
		{/foreach}	
		
		</ul></div>
		
	{/if}
</div>