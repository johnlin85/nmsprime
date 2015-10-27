		<table>
		<tr>
			<td>{{ Form::label('name', 'Name') }}</td>
			<td>{{ Form::text ('name') }}</td>
			<td>{{ $errors->first('name') }}</td>
		</tr>
		<tr>
			<td>{{ Form::label('hostname', 'Hostname') }}</td>
			<td>{{ Form::text ('hostname',null, array('readonly')) }}</td>
			<td>{{ $errors->first('hostname') }}</td>
		</tr>

		<tr>
			<td>{{ Form::label('mac', 'MAC address') }}</td>
			<td>{{ Form::text ('mac') }}</td>
			<td>{{ $errors->first('mac') }}</td>
		</tr>
		<tr>
			<td>{{ Form::label('configfile_id', 'Configfile') }}</td>
			<td>{{ Form::select('configfile_id', $configfiles) }}</td>
		</tr>
		<tr>
			<td>{{ Form::label('public', 'Public CPE') }}</td>
			<td>{{ Form::checkbox('public', 1) }}</td>
		</tr>

		<tr>
			<td>{{ Form::label('network_access', 'Network Access') }}</td>
			<td>{{ Form::checkbox('network_access', 1) }}</td>
		</tr>
		<tr>
			<td>{{ Form::label('qos_id', 'Qualities') }}</td>
			<td>{{ Form::select('qos_id', $qualities) }}</td>
		</tr>

		<tr>
			<td>{{ Form::label('serial_num', 'Serial Number') }}</td>
			<td>{{ Form::text('serial_num') }}</td>
		</tr>

		<tr>
			<td>{{ Form::label('inventar_num', 'Inventar Number') }}</td>
			<td>{{ Form::text('inventar_num') }}</td>
		</tr>

		<tr>
			<td>{{ Form::label('description', 'Description') }}</td>
			<td>{{ Form::textarea('description') }}</td>
		</tr>
		</table>
