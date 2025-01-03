<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Jogos</h1>
</div>

<div class="pb-3">
    {% if dates is defined AND dates | length %}
        {% for date, qt_matches in dates %}
            <div><a href="/backoffice/index/?dt_filter={{ date }}">{{ date }} | Total jogos: {{ qt_matches }}</a></div>
        {% endfor %}
    {% endif %}
</div>

<div class="table-responsive">
<table class="table table-hover" style="width: 600px;">
    {# <thead>
        <tr>
            <th class="d-none" style="width: 1%!important;">Data</th>
            <th style="width: 1%;">Estado</th>
            <th style="width: 230px;" class="text-center">Jogo</th>
            <th></th>
            <th></th>
        </tr>
    </thead> #}
    <tbody>
        {% if matches is defined AND matches | length %}
        {% for leagues in matches %}
            <tr class="table-secondary">
                <td colspan="5" class="text-left">{{ leagues["name"] }}</td>
            </tr>

            {% if leagues["matches"] is defined %}
            {% for match in leagues["matches"] %}
            <tr data-id="{{ match.id_match }}" onclick="window.location='/backoffice/detail/{{ match.id_match }}'" style="cursor: pointer;" title="clique para ver o detalhe">
                <td class="d-none d-md-block">{{ match.dt_match }}</td>
                <td style="width: 1%;">{{ mathStatus[match.id_match_status] }}</td>
                <td style="width: 230px;">
                    <div>
                        <img src="https://d3vild78lwlgil.cloudfront.net/flags/teams/{{ match.t1_id_team }}.png" style="width: 20px; height: auto;">
                        <span>{{ match.t1_ds_team }}</span>
                    </div>
                    <div>
                        <img src="https://d3vild78lwlgil.cloudfront.net/flags/teams/{{ match.t2_id_team }}.png" style="width: 20px; height: auto;">
                        <span>{{ match.t2_ds_team }}</span>
                    </div>
                </td>
                <td>
                    <div>{{ match.t1_qt_goal }}</div>
                    <div>{{ match.t2_qt_goal }}</div>
                </td>
                <td></td>
            </tr>
            {% endfor %}
            {% endif %}

        {% endfor %}
        {% endif %}
    </tbody>
</table>
</div>