{{ content() }}

<ul class="pager">
    <li class="previous pull-left">
        {{ link_to("users/index", "&larr; Go Back") }}
    </li>
    {#<li class="pull-right">
        {{ link_to("users/create", "Create users", "class": "btn btn-primary") }}
    </li>#}
</ul>

{% for user in page.items %}
{% if loop.first %}
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Utilizadores</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
		
<table class="table table-bordered table-striped" align="center">
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Profile</th>
            <th>Banned?</th>
            <th>Suspended?</th>
            <th>Confirmed?</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
{% endif %}
        <tr>
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.profile.name }}</td>
            <td>{{ user.banned == '1' ? 'Yes' : 'No' }}</td>
            <td>{{ user.suspended == '1' ? 'Yes' : 'No' }}</td>
            <td>{{ user.active == '1' ? 'Yes' : 'No' }}</td>
            <td width="12%">{{ link_to("users/edit/" ~ user.id, '<i class="icon-pencil"></i> Edit', "class": "btn btn-primary") }}</td>
            {#<td width="12%">
            	{{ link_to("users/delete/" ~ user.id, '<i class="icon-remove"></i> Delete', "class": "btn btn-danger") }}
            </td>
            #}
        </tr>
{% if loop.last %}
    </tbody>
    <tfoot>
        <tr>
            <td colspan="8" align="right">
                <div class="btn-group">
                    {{ link_to("users/search", '<i class="icon-fast-backward"></i> First', "class": "btn") }}
                    {{ link_to("users/search?page=" ~ page.before, '<i class="icon-step-backward"></i> Previous', "class": "btn ") }}
                    {{ link_to("users/search?page=" ~ page.next, '<i class="icon-step-forward"></i> Next', "class": "btn") }}
                    {{ link_to("users/search?page=" ~ page.last, '<i class="icon-fast-forward"></i> Last', "class": "btn") }}
                    <span class="help-inline">{{ page.current }}/{{ page.total_pages }}</span>
                </div>
            </td>
        </tr>
    </tfoot>
</table>

</div>
	</div>
</div>

{% endif %}
{% else %}
    No users are recorded
{% endfor %}
