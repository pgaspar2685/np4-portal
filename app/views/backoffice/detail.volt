<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detalhe do Jogo</h1>
</div>

<div class="mb-3">
    <div>
        <img src="https://d3vild78lwlgil.cloudfront.net/flags/teams/{{ match.t1_id_team }}.png" style="width: 20px; height: auto;"> {{ match.t1_ds_team }} - {{ match.t1_qt_goal }}
    </div>
    <div>
        <img src="https://d3vild78lwlgil.cloudfront.net/flags/teams/{{ match.t2_id_team }}.png" style="width: 20px; height: auto;"> {{ match.t2_ds_team }} - {{ match.t2_qt_goal }}
    </div>
</div>

<div class="mb-3">
    <div>Estado do jogo: </div>

    {% set id_status = match.id_match_status %}

    {% if match.id_match_status == 1 AND match.cd_half == 1 AND match.cd_match_status_half == 1 %}
        {% set id_status = 1000 %}
    {% elseif match.id_match_status == 1 AND match.cd_half == 1 AND match.cd_match_status_half == 0 %}
        {% set id_status = 1001 %}
    {% elseif match.id_match_status == 1 AND match.cd_half == 2 AND match.cd_match_status_half == 1 %}
        {% set id_status = 1002 %}
    {% endif %}

    {% if mathStatus is defined AND mathStatus | length %}
        {{ form('action': '/backoffice/setMatchStatus/' ~ match.id_match, 'id': 'form-match-status', 'class': 'needs-validation', 'novalidate': 'novalidate', 'onsubmit': 'return App.submitForm(this, {})', "autocomplete" : "off") }}
            <select class="form-control" name="cd_status" style="width: 200px; display: inline-block;">
            {% for id_estado, estado in mathStatus %}
                <option value="{{ id_estado }}" {{ id_estado == id_status ? "selected" : "" }}> {{ estado }}</option>
            {% endfor %}
            </select>
            <button class="btn btn-sm btn-primary shadow-sm">Alterar</button>
        {{ end_form() }}   
    {% endif %}
    
</div>

<div class="pt-3 h3 mb-0 text-gray-800">Eventos / Golos:</div>
<div class="mb-3">
    {% if match.events is defined AND match.events | length %}
    <div class="table-responsive">
        <table class="table" style="width: 600px">
            <thead>
                <tr>
                    <td style="width: 300px;">Equipa</td>
                    <td>Parte</td>
                    <td>Minuto</td>
                    <td colspan="2"></td>
                </tr>
            </thead>
            <tbody>
        {% for event in match.events %}
            {{ partial('backoffice/matches/eventsList', ['teams' : teams, 'event' : event]) }}
        {% endfor %}
            </tbody>
        </table>
    </div>
    {% else %}
        Sem eventos
    {% endif %}
</div>

{{ link_to("backoffice/insertGoal/" ~ match.id_match, 'Inserir Golo', "class": "btn btn-primary", "onclick" : "return App.showModalForm('/backoffice/insertGoal/"~ match.id_match ~ "', '#goalModal')") }}

{{ partial('backoffice/comentaries/form') }}

<!-- Goal Modal-->
<div class="modal fade" id="goalModal" tabindex="-1" role="dialog" aria-labelledby="goalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="goalModalLabel">Gestão - Golo</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-primary" href="#" onclick="return App.submitForm('#form-goal', {});">Gravar</a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal-->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Eliminar evento?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">

        </div>
      </div>
    </div>
  </div>

{#
<div class="table-secondary p-3">
    
</div>
#}