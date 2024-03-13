
const listContacts= async()=>{

    try {
        const response= await fetch('API.php')
        
        const data = await response.json();
        console.log(data);
        
    } catch (exc) {
        console.log(exc);
    }


}


window.addEventListener("load", async () => {

await listContacts();

});