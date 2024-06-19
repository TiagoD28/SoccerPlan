import {StyleSheet, Image, Dimensions, View} from 'react-native';
import React, { useState, useRef, toastRef, useEffect } from 'react';
import {SplashScreen} from 'react-native-splash-screen';


export default function Splash({ onLoaded }){
    const [isLoaded, setIsLoaded] = useState(false)
    useEffect(() => {
        const timer = setTimeout(() => {
          // O código que você deseja executar após 3 segundos
          setIsLoaded(true);
          console.log('Temporizador de 3 segundos expirou!');
          onLoaded();
        }, 3000);
    
        // Certifique-se de limpar o temporizador ao desmontar o componente
        return () => clearTimeout(timer);
      }, []);

    return(
        <View style={styles.container}>
            <Image style={styles.img} source={require('../assets/img/splash.png')} />
        </View>    
    );
}

// const windowWidth = Dimensions.get('window').width;
// const windowHeight = Dimensions.get('window').height

const styles = StyleSheet.create({
    container: {
        flex: 1,
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: 'white',
        // backgroundColor: '#041b2b',
    },  
    img: {
        width: 200,
        height: 200
    },
})