describe('Protractor Demo App', function() {
    it('should have a title', function() {
        browser.get('http://localhost:8046');

        var email = element(by.id('inputEmail'));

        var password = element(by.id('inputPassword'));

        email.sendKeys('Briantamu6@gmail.com');
    });
});