var customParsleyErrors = {
  'lt' : 'This number has to be lower than it\'s counterpart',
  'gt' : 'This number has to be higher than it\'s counterpart',
  'lte' : 'This number has to be lower or equal to it\'s counterpart',
  'gte' : 'This number has to be higher or equal to it\'s counterpart'
};

window.ParsleyValidator.addValidator('lt',
    function(value, requirement) {
      var compare = $(requirement).val();
      return ( parseInt(value) < parseInt(compare) );
    }, 32).addMessage('en', 'lt', customParsleyErrors['lt']);

window.ParsleyValidator.addValidator('gt',
    function(value, requirement) {
      var compare = $(requirement).val();
      return ( parseInt(value) > parseInt(compare) );
    }, 32).addMessage('en', 'gt', customParsleyErrors['gt']);

window.ParsleyValidator.addValidator('lte',
    function(value, requirement) {
      var compare = $(requirement).val();
      return ( parseInt(value) <= parseInt(compare) );
    }, 32).addMessage('en', 'lte', customParsleyErrors['lte']);

window.ParsleyValidator.addValidator('gte',
    function(value, requirement) {
      var compare = $(requirement).val();
      return ( parseInt(value) >= parseInt(compare) );
    }, 32).addMessage('en', 'gte', customParsleyErrors['gte']);


