/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


var ablefutures = ablefutures || {collections : {}, app : {}, views : {}, models : {}};

ablefutures.collections.links = Backbone.Collection.extend({
    model: ablefutures.models.link,
    
    url : "api/getLinks.php"
});