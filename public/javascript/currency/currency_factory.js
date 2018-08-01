app.factory('Currency', function($http) {

	// instantiate our initial object
	var Currency = function(currencyExchange) {
		this.currencyExchange = currencyExchange;
	};

	// define the getProfile method which will fetch data
	// from GH API and *returns* a promise
	Currency.prototype.getRates = function() {

		// Generally, javascript callbacks, like here the $http.get callback,
		// change the value of the "this" variable inside it
		// so we need to keep a reference to the current instance "this" :
		var self = this;

		return $http.get('/getCurrencyInfo/' + this.currencyExchange).then(function(response) {

			// when we get the results we store the data in user.profile
			self.rates = response.data.rates;
			self.maxRate = response.data.maxRate;
			self.minRate = response.data.minRate;
			self.rateCount = response.data.rateCount;

			// promises success should always return something in order to allow chaining
			return response;

		});
	};
	return Currency;
})