
const listContacts= async()=>{

    try {
        const response= await fetch('API.php')
        
        const data = await response.json();
        console.log(data);
        
    } catch (ex) {
        console.log(ex);
    }


}


window.addEventListener("load", async () => {

await listContacts();

});