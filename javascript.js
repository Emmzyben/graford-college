function toggleDropdown() {
    const subMenu = document.querySelector('.sub-menu1');
    subMenu.style.display = (subMenu.style.display === 'none' || subMenu.style.display === '') ? 'block' : 'none';
  }

        let isMenuOpen = false;
        
        const toggleMenu = () => {
            const menu = document.getElementById("ul");
            
            if (!isMenuOpen) {
                menu.style.height = "auto";
                isMenuOpen = true;
            } else {
                menu.style.height = "0px";
                isMenuOpen = false;
            }
        };
        
        const closeMenu = () => {
            const menu = document.getElementById("ul");
            menu.style.height = "0px";
            isMenuOpen = false;
        };




        const imageUrls = [
            'images/airport1.jpg',
            'images/pic7.jpg',
            'images/pic2.jpg',
            'images/pic3.jpg',
            'images/pic4.jpg',
            'images/pic6.jpg',
            'images/pic5.webp',
        
        ];
        
        let currentIndex = 0;
        
        function updateImage() {
            const rotatingImage = document.getElementById('rotating-image');
            rotatingImage.src = imageUrls[currentIndex];
        }
        
        function goToPrevious() {
            currentIndex = currentIndex === 0 ? imageUrls.length - 1 : currentIndex - 1;
            updateImage();
        }
        
        function goToNext() {
            currentIndex = (currentIndex + 1) % imageUrls.length;
            updateImage();
        }
        
        // Automatically rotate images every 3 seconds
        setInterval(goToNext, 3000);
        
        // Initial image update
        updateImage();
        

        function vocational(){
            var open = false;
            var option= document.getElementById('vocational').style.display;
            if(option === "none"){
                 open= true;
                 document.getElementById('vocational').style.display='block';
            }else{
                open =false;
                document.getElementById('vocational').style.display='none';
            }
        }