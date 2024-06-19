import React, { useState } from "react";
import {View, Text, StyleSheet, Dimensions, Button, TouchableOpacity, TouchableHighlight, Modal, SafeAreaView, Platform, FlatList, Image} from 'react-native';
import Icon from 'react-native-vector-icons/Feather';
import showToast from "../Toast/Toast";
import Config from '../../getPostApi/config'
import useData from "../../getPostApi/useData";

export default function SelectComponent({ options, onChangeSelect, text, label, isTeam }){
    const [txt, setTxt] = useState(text);
    const [selected, setSelected] = useState('');
    const [modalVisible, setModalVisible] = useState(false);
    
    // console.log('Options: ',options);

    function renderOption(item){
        return(
            // <TouchableOpacity style={[styles.optionContainer, {backgroundColor: item.id === selected ? 'gray' : 'white'}]} 
            <>
            {!isTeam ? (
                <TouchableOpacity style={styles.optionContainer}
                onPress={() => {
                    onChangeSelect(item.idClub)
                    setTxt(item.nameClub)
                    setModalVisible(false)
                    setSelected(item.idClub)
                }}>
                    <View style={styles.left}>
                        {/* <Image source={{uri:item.img + '?img=' + item.id}}/> */}
                        {!item.img ? (
                            // <Image source={{uri:item.img}} style={styles.img} />
                            <View style={[styles.img, styles.circularFill]} />
                        ) : (
                            <Image source={{uri:item.img}} style={styles.img} />
                        )
                        }
                        
                        <Text style={styles.optionTxt}>{item.nameClub}</Text>
                        {/* {isTeam && (
                            <Text style={styles.optionTxt}>   Idades: {item.age}</Text>
                        )} */}
                    </View>
                    {item.idClub === selected && (
                        <Icon name="check" size={20} color={'#f50443'} />
                    )}
                </TouchableOpacity>
            ) : (
                <TouchableOpacity style={styles.optionContainer}
            onPress={() => {
                onChangeSelect(item.idTeam)
                setTxt(item.nameTeam)
                setModalVisible(false)
                setSelected(item.idTeam)
            }}>
                <View style={styles.left}>
                    {/* <Image source={{uri:item.img + '?img=' + item.id}}/> */}
                    {/* <Image source={{uri:item.img}} style={styles.img} /> */}
                    <Text style={styles.optionTxt}>{item.nameTeam}</Text>
                    {isTeam && (
                        <Text style={styles.optionTxt}>   Idades: {item.age}</Text>
                    )}
                </View>
                {item.idTeam === selected && (
                    <Icon name="check" size={20} color={'#f50443'} />
                )}
            </TouchableOpacity>
            )}
            </>
            
        )
    }

    return(
        <>
            <Text style={styles.label}>{label}</Text>
            <TouchableOpacity style={styles.container} onPress={() => setModalVisible(true)} >
            {/* <TouchableOpacity style={styles.container} onPress={() => [setModalVisible(true), useData(Config.apiUrl+"Clubs?route=getClubs").data]} > */}
                <Text style={{color: 'black'}} numberOfLines={1}>{txt}</Text>
                <Icon name="chevron-down" color={'black'} size={20} marginRight={12} />
            </TouchableOpacity>
            <Modal 
                animationType="slide" 
                visible={modalVisible} 
                onRequestClose={() => setModalVisible(false)} 
            >
                <SafeAreaView>
                    <View style={styles.headerModal}>
                        <TouchableOpacity onPress={() => setModalVisible(false)} >
                            <Icon name="chevron-left" color={'black'} size={30} padding={10} />
                        </TouchableOpacity>
                        <Text style={styles.modalTitle}>{text}</Text>
                        <TouchableOpacity onPress={() => setModalVisible(false)} >
                            <Text style={styles.modalCancel}>Cancelar</Text>
                        </TouchableOpacity>
                    </View>
                    {!isTeam ? (
                        <FlatList
                            data={options}
                            keyExtractor={(item) => String(item.idClub)} 
                            renderItem={({item}) => renderOption(item)}
                        />
                    ) : (
                        <FlatList
                            data={options}
                            keyExtractor={(item) => String(item.idTeam)} 
                            renderItem={({item}) => renderOption(item)}
                        />
                    )}
                </SafeAreaView>
            </Modal>
        </>
    )
}

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const styles = StyleSheet.create({
    container: {
        width: windowWidth,
        height: 60,
        backgroundColor: "white",
        paddingLeft: 12,
        marginTop: 20,
        fontSize: 18,
        borderColor: 'gray',
        borderWidth: 1,
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
    },
    headerModal: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        paddingHorizontal: 12,
        borderBottomColor: 'gray',
        borderBottomWidth: 1,
        paddingBottom: 12,
        marginTop: Platform.OS == 'android' ? 5 : 0,
    },
    modalTitle: {
        fontSize: 18,
        color: 'black',
    },
    modalCancel: {
        fontSize: 14,
        color: 'black',
        fontWeight: 'bold'
    },
    optionContainer: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        borderBottomColor: 'white',
        borderBottomWidth: 1,
        padding: 10
    },
    optionTxt: {
        fontSize: 14,
        color: 'black'
    },
    label: {
        marginLeft: -340,
        marginBottom: -10,
        fontSize: 14,
    },
    left: {
        flexDirection: 'row',
        alignItems: 'center'
    },
    img: {
        height: 50,
        width: 50,
        borderRadius: 25,
        marginRight: 12,
    },
    circularFill: {
        backgroundColor: 'gray', // Set the background color for the circular fill
    },
  });


    // This function makes a flatlist without photo
    // function renderOption (item) {
    //     // console.log(item)
    //     return(
    //         <TouchableOpacity 
            // style={[styles.optionContainer, {backgroundColor: item.id === selected ? 'gray' : 'white'}]} 
            // onPress={() => {
            //     onChangeSelect(item.id)
            //     setTxt(item.name)
            //     setModalVisible(false)
            //     setSelected(item.id)
            // }}>
    //             {/* <Text style={[styles.optionTxt, {color: item.id === selected ? '#f50443' : 'gray'}]} >{item.name}</Text> */}
    //             <Text style={[styles.optionTxt, {fontWeight: item.id === selected ? 'bold' : 'normal'}]} >{item.name}</Text>
    //             {item.id === selected && (
    //                 <Icon name="check" size={20} color={'#f50443'} />
    //             )}
    //         </TouchableOpacity>
    //     )
    // }