/**
 * upon click submit, build a url around user entered search term and redirect
 *
 * @param  {string} term user entered search term
 * @return {boolean}     false to prevent normal form operation
 */
function edan_search_redirect(term)
{
  var search = document.getElementById('edan-search-bar').value;
  search = search.trim();
  search = search.replace(/\s/g, '+');
  console.log(window.location.href);
  console.log(search);

  let url = new URL(window.location.href);
  let params = new URLSearchParams(url.search.slice(1));

  params = new URLSearchParams();
  //params.set('edan_q', search.replace(" ", "%2B"));
  params.set('edan_q', search);
  //params.set('edan_fq[]', 'type:edanmdm');

  //console.log("URL: " + url.toString().split('?')[0]);
  //console.log("PARAMS: " + params.toString());
  console.log(encodeURI(url.toString().split('?')[0] + '?' + params.toString()))

  window.location.replace(url.toString().split('?')[0] + '?' + params.toString());

  return false;
}
