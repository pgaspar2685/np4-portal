<tr>
    <td>{{ teams[event.id_team] }}</td>
    <td>{{ event.cd_half }}</td>
    <td>{{ event.cd_minute }}</td>
    <td width="12%">{{ link_to("backoffice/editGoal/" ~ event.id_event, 'Editar', "class": "btn btn-primary", "onclick" : "return App.showModalForm('/backoffice/editGoal/"~ event.id_event ~ "', '#goalModal')") }}</td>
    <td width="12%">{{ link_to("backoffice/deleteGoal/" ~ event.id_event, 'Apagar', "class": "btn btn-danger", "onclick" : "return App.showModalForm('/backoffice/deleteGoal/"~ event.id_event ~ "', '#deleteModal')") }}</td>
</tr>

