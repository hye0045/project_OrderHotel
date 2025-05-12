const toggler = document.querySelector(".toggler-btn");
toggler.addEventListener("click",function(){
    document.querySelector("#sidebar").classList.toggle("collapsed")
})

//Toggle Dropdown
const items = document. querySelectorAll(".has-dropdown")

function toggle(){
    const itemToggle= this.classList.contains("active");
    for(i=0; i<items.length; i++){
        items[i].classList.remove("active");
    }
    if( !itemToggle){
        this.classList.add("active");
    }
}

items.forEach((item)=> item.addEventListener("click", toggle));