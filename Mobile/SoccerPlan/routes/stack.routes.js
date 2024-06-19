import { createNativeStackNavigator } from "@react-navigation/native-stack";
import { Feather } from "@expo/vector-icons";
// import { useNavigation } from '@react-navigation/native';

import Login from "../screens/Authentication/Login";
import Register from "../screens/Authentication/Register";


const Stack = createNativeStackNavigator();

export default function StackRoutes({onLoginSuccess}){
    
    return(
        <Stack.Navigator screenOptions={{headerShown: false}}>
            <Stack.Screen 
                name="Login"
                // component={(props) => <Login {...props} onLoginSuccess={onLoginSuccess} />}
                // component={() => <Login onLoginSuccess={onLoginSuccess} />}
            >
                {(props) => <Login {...props} onLoginSuccess={onLoginSuccess} />}
            </Stack.Screen>
            <Stack.Screen 
                name="Register"
                component={Register}
            />
        </Stack.Navigator>
    )
}