<h1>Company quote</h1>

<h2>Quote</h2>

<p><b>Email:</b> {{ $request['email'] }}</p>
<p><b>Tel:</b> {{ $request['tel'] }}</p>
<p><b>Text:</b> {{ $request['quote'] }}</p>

<h2>Company</h2>

<p><b>ID:</b> {{ $company['id'] }}</p>
<p><b>Category:</b> {{ $company['category']['name'] }}</p>
<p><b>Name:</b> <a href="{{ $company['url'] }}" target="_blank">{{ $company['name'] }}</a></p>
<p><b>Address:</b> {{ $company['address'] }}</p>
<p><b>Tel:</b> {{ $company['tel'] }}</p>
<p><b>Website:</b> {{ $company['website'] }}</p>
<p><b>Description:</b> {{ $company['description'] }}</p>
<p><b>Price range:</b> {{ $company['price_range'] }}</p>
