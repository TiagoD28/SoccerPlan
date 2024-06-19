import { NavigationContainer } from '@react-navigation/native';

import StackRoutes from './stack.routes';
import {CoachTabRoutes, PlayerTabRoutes} from './tab.routes';
//import TabRoutes from './tab.routes';
//import DrawerRoutes from './drawer.routes';
// drawer e para o caso de querer usar a barra menu no canto

export function RoutesAuthentication({ onLogged }) {
    return(
        <NavigationContainer>
            <StackRoutes onLoginSuccess={onLogged} />
        </NavigationContainer>
    )
}

export function CoachRoutes({userData, setIsLogged}) {
    return(
        <NavigationContainer>
            <CoachTabRoutes userData={userData} setIsLogged={setIsLogged}/>
        </NavigationContainer>
    )
}

export function PlayerRoutes({userData, setIsLogged}) {
    return(
        <NavigationContainer>
            <PlayerTabRoutes userData={userData} setIsLogged={setIsLogged}/>
        </NavigationContainer>
    )
}