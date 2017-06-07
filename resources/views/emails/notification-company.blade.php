<h1>Notification company</h1>

<h2>Company</h2>
<p><b>ID:</b> {{ $company['id'] }}</p>
<p><b>Category:</b> {{ $company['category']['name'] }}</p>
<p><b>Name:</b> {{ $company['name'] }}</p>
<p><b>Address:</b> {{ $company['address'] }}</p>
<p><b>Tel:</b> {{ $company['tel'] }}</p>
<p><b>Website:</b> {{ $company['website'] }}</p>
<p><b>Description:</b> {{ $company['description'] }}</p>
<p><b>Price range:</b> {{ $company['price_range'] }}</p>

<h2>Contact person</h2>

<p><b>Name:</b> {{ @$request['contact_name'] }} {{ @$request['contact_surname'] }}</p>
<p><b>Tel:</b> {{ @$request['contact_tel'] }}</p>
<p><b>Email:</b> {{ @$request['contact_email'] }}</p>