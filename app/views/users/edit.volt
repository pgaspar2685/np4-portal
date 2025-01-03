          	            	
<form method="post" autocomplete="off">

	<div class="float-right mt-2">
	{{ link_to("users", "&larr; Go Back", "class": "btn btn-primary mr-2") }}
	{{ link_to("users/ban/" ~ user.id, '<i class="fas fa-user-slash"></i> Banir Telegram', "class": "btn btn-danger mr-2") }}
	{{ link_to("users/unban/" ~ user.id, '<i class="fas fa-user-check"></i> Permitir Telegram', "class": "btn btn-success") }}
	</div>

	<hr>

	{{ content() }}

	<div class="center scaffold">
	    <h2>Edit users</h2>
	
	    <ul class="nav nav-tabs">
	        <li class="nav-item active"><a class="nav-link" href="#A" data-toggle="tab">Informação principal</a></li>
	        <li class="nav-item"><a class="nav-link" href="#B" data-toggle="tab">Login com sucesso</a></li>
	        <li class="nav-item"><a class="nav-link" href="#C" data-toggle="tab">Alteração de password</a></li>
	        <li class="nav-item"><a class="nav-link" href="#D" data-toggle="tab">Recuperação de Passwords</a></li>
	        <li class="nav-item"><a class="nav-link" href="#E" data-toggle="tab">Pagamentos</a></li>
	        <li class="nav-item"><a class="nav-link" href="#F" data-toggle="tab">Ofertas</a></li>
	    </ul>

		<div class="tabbable">
		    <div class="tab-content">
		        <div class="tab-pane active" id="A">
		
		            {{ form.render("id") }}
		
		            <div class="span4">
		
		                <div class="clearfix">
		                    <label for="name">Name</label>
		                    {{ form.render("name") }}
		                </div>
		
		                <div class="clearfix">
		                    <label for="profilesId">Profile</label>
		                    {{ form.render("id_profile") }}
		                </div>
		
		                <div class="clearfix">
		                    <label for="suspended">Suspended?</label>
		                    {{ form.render("fl_suspended") }}
		                </div>
		
		            </div>
		
		            <div class="span4">
		
		                <div class="clearfix">
		                    <label for="email">E-Mail</label>
		                    {{ form.render("email") }}
		                </div>
		
		                <div class="clearfix">
		                    <label for="banned">Banned?</label>
		                    {{ form.render("fl_banned") }}
		                </div>
		
		                <div class="clearfix">
		                    <label for="active">Confirmed?</label>
		                    {{ form.render("fl_active") }}
		                </div>
		
		            </div>
		        </div>
		
		        <div class="tab-pane" id="B">
		            <p>
		                <table class="table table-bordered table-striped" align="center">
		                    <thead>
		                        <tr>
		                            <th>Id</th>
		                            <th>IP Address</th>
		                            <th>User Agent</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    {% for login in user.successLogins %}
		                        <tr>
		                            <td>{{ login.id }}</td>
		                            <td>{{ login.ip_address }}</td>
		                            <td>{{ login.user_agent }}</td>
		                        </tr>
		                    {% else %}
		                        <tr><td colspan="3" align="center">User does not have successfull logins</td></tr>
		                    {% endfor %}
		                    </tbody>
		                </table>
		            </p>
		        </div>
		
		        <div class="tab-pane" id="C">
		            <p>
		                <table class="table table-bordered table-striped" align="center">
		                    <thead>
		                        <tr>
		                            <th>Id</th>
		                            <th>IP Address</th>
		                            <th>User Agent</th>
		                            <th>Date</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    {% for change in user.passwordChanges %}
		                        <tr>
		                            <td>{{ change.id }}</td>
		                            <td>{{ change.ip_address }}</td>
		                            <td>{{ change.useragent }}</td>
		                            <td>{{ date("Y-m-d H:i:s", change.dt_created) }}</td>
		                        </tr>
		                    {% else %}
		                        <tr><td colspan="3" align="center">User has not changed his/her password</td></tr>
		                    {% endfor %}
		                    </tbody>
		                </table>
		            </p>
		        </div>
		
		        <div class="tab-pane" id="D">
		            <p>
		                <table class="table table-bordered table-striped" align="center">
		                    <thead>
		                        <tr>
		                            <th>Id</th>
		                            <th>Date</th>
		                            <th>Reset?</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    {% for reset in user.resetPasswords %}
		                        <tr>
		                            <th>{{ reset.id }}</th>
		                            <th>{{ date("Y-m-d H:i:s", reset.createdAt) }}</th>
		                            <th>{{ reset.reset == '1' ? 'Yes' : 'No' }}</th>
		                        </tr>
		                    {% else %}
		                        <tr><td colspan="3" align="center">User has not requested reset his/her password</td></tr>
		                    {% endfor %}
		                    </tbody>
		                </table>
		            </p>
		        </div>
		        
		        <div class="tab-pane" id="E">
		            <p>
		                <table class="table table-bordered table-striped" align="center">
		                    <thead>
		                        <tr>
		                            <th>Id</th>
		                            <th>Data</th>
		                            <th>Estado</th>
		                            <th>Pacote</th>
		                            <th>Dias</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    {% for payment in user.UserPayment %}
		                        <tr>
		                            <th>{{ payment.id }}</th>
		                            <th>{{ date("Y-m-d H:i:s", payment.dt_created) }}
		                            <th>{{ payment.cd_status }}
		                            <th>{{ payment.cd_package }}
		                            <th>{{ payment.qt_days }}
		                        </tr>
		                    {% else %}
		                        <tr><td colspan="5" align="center">Utilizador sem pedido de pagamento</td></tr>
		                    {% endfor %}
		                    </tbody>
		                </table>
		            </p>
		        </div>
		        
		        <div class="tab-pane" id="F">
		
		                <table class="table table-bordered table-striped" align="center">
		                    <thead>
		                        <tr>
		                            <th>Id</th>
		                            <th>Data</th>
		                            <th>Dias</th>
		                            <th>Perfil</th>
		                            <th>Última data VIP</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    {% for oferta in user.userOfertas %}
		                        <tr>
		                            <th>{{ oferta.id }}</th>
		                            <th>{{ date("Y-m-d H:i:s", oferta.dt_offer) }}</th>
		                            <th>{{ oferta.qt_days }}</th>
		                            <th>{{ oferta.id_profile }}</th>
		                            <th>{{ date("Y-m-d H:i:s", oferta.dt_vip_expired) }}</th>
		                        </tr>
		                    {% else %}
		                        <tr><td colspan="5" align="center">Utilizador não teve ofertas</td></tr>
		                    {% endfor %}
		                    </tbody>
		                </table>
		 
		        </div>
		
		    </div>
		</div>
	</div>
	
	<hr>
	{{ submit_button("Save", "class": "btn btn-big btn-success") }}
	
</form>
