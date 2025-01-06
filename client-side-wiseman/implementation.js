//I HATE JAVASCRIPT 
// My Ever Growing Hatred for this language i swear 

//Reading The Url Functions 
function read_cabinet_url_code(){
    var url = get_current_url(); 
    if (url.searchParams.get('cabinet')){
        return url.searchParams.get('cabinet'); 
    }    

}

function read_journal_url_code(){
    var url = get_current_url(); 
    if (url.searchParams.get('journal')){
        return url.searchParams.get('journal'); 
    }    
}

function read_notes_url_code(){
    var url = get_current_url(); 
    if (url.searchParams.get('notes')){
        return url.searchParams.get('notes'); 
    }    
}

function read_article_url_code(){
    var url = get_current_url(); 
    if (url.searchParams.get('article')){
        return url.searchParams.get('article'); 
    }
}

function get_current_url(){
    let url = new URL(window.location.href);
    return url; 
}

const dbName = "blogDatabase";
const storeName = "blogPosts";
let db;

const request = indexedDB.open(dbName, 1);

request.onerror = function(event) {
    console.error("IndexedDB error:", event.target.errorCode);
};

request.onupgradeneeded = function(event) {
    db = event.target.result;
    if (!db.objectStoreNames.contains(storeName)) {
        db.createObjectStore(storeName, { keyPath: 'id' });
    }
};
