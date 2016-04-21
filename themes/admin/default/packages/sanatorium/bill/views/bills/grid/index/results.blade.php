<script type="text/template" data-grid="bill" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="<%= r.edit_uri %>" href="<%= r.edit_uri %>"><%= r.id %></a></td>
			<td><%= r.num %></td>
			<td><%= r.issue_date %></td>
			<td><%= r.due_date %></td>
			<td><%= r.means_of_payment %></td>
			<td><%= r.payment_symbol %></td>
			<td><%= r.account_number %></td>
			<td><%= r.iban %></td>
			<td><%= r.swift %></td>
			<td><%= r.buyer_id %></td>
			<td><%= r.supplier_id %></td>
			<td><%= r.year %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
