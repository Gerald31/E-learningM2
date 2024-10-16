<script setup>
import {computed, inject, onBeforeMount, onMounted, onUnmounted, ref, watch} from "vue";

import BaseIcon from "@/components/BaseIcon.vue";
import Bottom from "@/components/messenger/chat/bottom/Bottom.vue";
import Middle from "@/components/messenger/chat/middle/Middle.vue";
import TypeIndicator from "@/components/messenger/chat/middle/TypeIndicator.vue";
import Top from "@/components/messenger/chat/top/Top.vue";


import {
    mdiVideo,
    mdiVideoOff,
    mdiPhoneHangup,
    mdiMicrophone,
    mdiMicrophoneOff,
    mdiArrowUpBox,
    mdiCloseBox
} from "@mdi/js";

const $pusher = inject('$pusher');

const api = inject('$api');

import { useAuthStore, useMessengerStore } from "@/stores";

const messengerStore = useMessengerStore();

const authStore = useAuthStore();

const activeConversation = computed(() => messengerStore.filteredConversations());
const isActiveVideo = computed(() => messengerStore.openVideo);

// the message you want to reply to
const selectedMessageToReplyTo = ref();

const ICE_SERVERS = {
    // you can add TURN servers here too
    iceServers: [
        {
            urls: 'stun:openrelay.metered.ca:80'
        },
        {
            urls: 'stun:stun.l.google.com:19302',
        },
        {
            urls: 'stun:stun2.l.google.com:19302',
        },
    ],
};

const messagesContainer = ref(null);
const selfview = ref(null);
const remoteview = ref(null);
const showEndCallButton = ref(false);

const messagesChannel = ref(null)

const users = ref([]);
const room = ref();
const caller = ref([]);
const localUserMedia = ref();
const micActive = ref(true);
const cameraActive = ref(true);
const localScreen = ref();
const shareScreen = ref();
const shareScreenActive = ref(false);

const currentUser = authStore.user.id;
const videoContainer = document.getElementById('video-container');


// set the selected message for a reply
const selectMessageToReplyTo = (message) => {
    selectedMessageToReplyTo.value = message;
};

// remove the selected message for reply
const removeMessageToReplyTo = () => {
    selectedMessageToReplyTo.value = undefined;
}

onBeforeMount(() => {
    //To iron over browser implementation anomalies like prefixes
    GetRTCPeerConnection();
    // GetRTCSessionDescription();
    GetRTCIceCandidate();
    prepareCaller();
})

onMounted(() => {
    scrollToBottom();
    messagesChannel.value = $pusher.subscribe('private-chat');
    setActiveStatus(true);

    // listen to messaging event
    messagesChannel.value.bind('messaging', (data) => {
        if (Number(data.tutorat_id) === Number(messengerStore.activeTutoratId) && data.from_id !== authStore.user.id) {
            messengerStore.updateMessages(data.message);
            // scroll to bottom
            scrollToBottom();
        }
    });

    // listen to setActiveStatus event
    messagesChannel.value.bind("client-set-active-status", function (data) {
        if (Number(data.tutorat_id) === Number(messengerStore.activeTutoratId) && data.user_id !== authStore.user.id) {
            // console.log('client-set-active-status =>', data)
            messengerStore.setActiveStatus(data.user_id, data.status);
        }
    });

    // listen to typing indicator
    messagesChannel.value.bind("client-typing", function (data) {
        if (Number(data.tutorat_id) === Number(messengerStore.activeTutoratId) && data.from_id !== authStore.user.id) {
            if (data.typing) {
                // add
                messengerStore.addTypingIndicator(data.from_id);
            } else {
                // remove
                messengerStore.removeTypingIndicator(data.from_id);
            }
        }
        // scroll to bottom
        scrollToBottom();
    });

    //Listening for the candidate message from a peer sent from onicecandidate handler
    messagesChannel.value.bind("client-candidate", async(data) => {
        if (Number(data.tutorat_id) === Number(messengerStore.activeTutoratId) && data.from_id !== authStore.user.id) {
            await caller.value[data.from_id].addIceCandidate(new RTCIceCandidate(data.candidate));
        }
    });

    messagesChannel.value.bind("client-sdp", async (data) => {
        if (Number(data.tutorat_id) === Number(messengerStore.activeTutoratId) && data.from_id !== authStore.user.id) {
            await bindingSdp(data);
        }
    });

    messagesChannel.value.bind("client-share-screen", async (data) => {
        if (Number(data.tutorat_id) === Number(messengerStore.activeTutoratId) && data.from_id !== authStore.user.id) {
            await caller.value.addStream(data.mediaStream);
            localScreen.value.srcObject = await caller.value.getRemoteStreams();
        }
    });

    messagesChannel.value.bind("remove-share-screen", async (data) => {
        if (Number(data.tutorat_id) === Number(messengerStore.activeTutoratId) && data.from_id !== authStore.user.id) {
            await caller.value.removeStream(data.id);
            localScreen.value.srcObject = null;
        }
    });

    //Listening for answer to offer sent to remote peer
    messagesChannel.value.bind("client-answer", async(data) => {
        if (Number(data.tutorat_id) === Number(messengerStore.activeTutoratId) && data.from_id !== authStore.user.id) {
            await caller.value[data.from_id].setRemoteDescription(data.sdp);
        }
    });

    messagesChannel.value.bind("client-endcall", (data) => {
        if (Number(data.tutorat_id) === Number(messengerStore.activeTutoratId) && data.from_id !== authStore.user.id) {
            endCall();
        }
    });

    // listen to seen event
    messagesChannel.value.bind("client-seen", function (data) {
        if (Number(data.tutorat_id) === parseInt(messengerStore.activeTutoratId) && data.from_id !== authStore.user.id) {
            // messengerStore.setTypingIndicator(data.typing);
        }
    });

    // listen on message delete event
    messagesChannel.value.bind("client-messageDelete", function (data) {
        if (Number(data.tutorat_id) === parseInt(messengerStore.activeTutoratId) && data.from_id !== authStore.user.id) {
            // messengerStore.deleteMessage(data.message_id);
        }
    });
});

watch(isActiveVideo, async(activeVideo) => {
    if (activeVideo) {
        await initializeDevice();
    } else {
        localUserMedia.value?.srcObject.getTracks()
            .forEach((track) => track.stop());
    }
}, { deep: true });

onUnmounted(() => {
    setActiveStatus(false);
    endCurrentCall();
    $pusher.unsubscribe('private-chat');
    messagesChannel.value = null;
});

const scrollToBottom = () => {
    messagesContainer.value.scrollTop = messagesContainer.value?.scrollHeight;
}

const downloadAttachment = (message) => {
    const fileName = message.attachment.name;
    api.messengerApi.download(message.id).then((response) => {
        const fileURL = window.URL.createObjectURL(new Blob([response]));
        const downloadLink = document.createElement('a');
        downloadLink.setAttribute('download', fileName);
        downloadLink.href = fileURL;
        downloadLink.click();
    });
}

/**
 *-------------------------------------------------------------
 * Trigger typing event
 *-------------------------------------------------------------
 */
const isTyping = (status) => {
    return messagesChannel.value.trigger("client-typing", {
        from_id: authStore.user?.id, // Me
        tutorat_id: messengerStore.activeTutoratId, // Messenger
        typing: status,
    });
}

const setActiveStatus = (status) => {
    return messagesChannel.value.trigger("client-set-active-status", {
        user_id: authStore.user?.id, // Me
        tutorat_id: messengerStore.activeTutoratId, // Messenger
        status: status,
    });
}

/*************/
const prepareCaller = async() => {
    //Initializing a peer connection
    caller.value[currentUser] = new window.RTCPeerConnection({
       /* iceServers: [{
            urls: urlStun.value
        }]*/
    });
    //Listen for ICE Candidates and send them to remote peers
    caller.value[currentUser].oniceconnectionstatechange = (event) => checkPeerDisconnect(event, currentUser);
    // We implement our onicecandidate method for when we received a ICE candidate from the STUN server
    caller.value[currentUser].onicecandidate = handleICECandidateEvent;

    //onaddstream handler to receive remote feed and show in remoteview video element
    // We implement our onTrack method for when we receive tracks
    caller.value[currentUser].ontrack = event => setRemoteStream(event, currentUser);
}

const setRemoteStream = (event, remotePeerId) => {
    console.log('Remote stream set')
    if(event.track.kind === "video") {
        const videoRemote = document.createElement('video');
        videoRemote.srcObject = event.streams[0];
        videoRemote.id = 'remotevideo_' + remotePeerId;
        videoRemote.setAttribute('autoplay', '');
        videoRemote.style.backgroundColor = "red";
        videoContainer.append(videoRemote);
    }
    updateLayout();
}

const checkPeerDisconnect = (event, userId) => {
    const state = caller.value[userId].iceConnectionState;
    console.log(`connection with peer ${userId}: ${state}`);
    if (state === "failed" || state === "closed" || state === "disconnected") {
        console.log(`Peer ${userId} has disconnected`);
        const videoDisconnected = document.getElementById('remotevideo_' + userId)
        videoDisconnected.remove();
    }
    updateLayout();
}

const handleICECandidateEvent = async (event) => {
    if (event.candidate) {
        // return sentToPusher('ice-candidate', event.candidate)
        messagesChannel.value?.trigger("client-candidate", {
            tutorat_id: messengerStore.activeTutoratId, // Messenger
            candidate: event.candidate,
            from_id: authStore.user?.id, // Me
        });
    }
}

const getCamera = () => {
    //Get local audio/video feed and show it in selfview video element
    return navigator.mediaDevices.getUserMedia({
        video: {
            width: { max: 320 },
            height: { max: 240 },
            frameRate: { max: 30 },
        },
        audio: true
    });
}

const updateLayout = () => {
    console.log('_________ ______>')
    console.log('updating layout');
    // update CSS grid based on number of diplayed videos
    let rowHeight = '98vh';
    let colWidth = '98vw';

    var numVideos = Object.keys(caller.value).length - 1; // add one to include local video

    if (numVideos > 0 && numVideos <= 4) { // 2x2 grid
        rowHeight = '48vh';
        colWidth = '48vw';
    } else if (numVideos > 4) { // 3x3 grid
        rowHeight = '32vh';
        colWidth = '32vw';
    }

    document.documentElement.style.setProperty(`--rowHeight`, rowHeight);
    document.documentElement.style.setProperty(`--colWidth`, colWidth);
}

const GetRTCIceCandidate = () => {
    window.RTCIceCandidate = window.RTCIceCandidate || window.webkitRTCIceCandidate ||
        window.mozRTCIceCandidate || window.msRTCIceCandidate;
    return window.RTCIceCandidate;
}

const GetRTCPeerConnection = () => {
    window.RTCPeerConnection = window.RTCPeerConnection || window.webkitRTCPeerConnection ||
        window.mozRTCPeerConnection || window.msRTCPeerConnection;
    return window.RTCPeerConnection;
}

//Create and send offer to remote peer on button click
const initializeDevice = async () => {
    const stream = await getCamera();
    selfview.value.srcObject = stream;
    selfview.value.muted = true;
    toggleEndCallButton();

    localUserMedia.value = stream;
    addLocalTracks(caller.value[currentUser]);

    await createOffer(caller.value[currentUser], currentUser);
    room.value = messengerStore.activeTutoratId;
};

const endCall = () => {
    caller.value[currentUser].close();
    localUserMedia.value?.getTracks()
        .forEach((track) => track.stop());
    prepareCaller();
    toggleEndCallButton();
}

const endCurrentCall = () => {
    messagesChannel.value.trigger("client-endcall", {
        tutorat_id: messengerStore.activeTutoratId, // Messenger
        from_id: authStore.user?.id, // Me
    });
    endCall();
}

const toggleEndCallButton = () => {
    showEndCallButton.value = !showEndCallButton.value;
}

//Listening for Session Description Protocol message with session details from remote peer
const bindingSdp = async (data) => {
    await caller.value[data.from_id].setRemoteDescription(data.sdp);
    const stream = await getCamera();
    localUserMedia.value = stream;
    selfview.value.srcObject = stream;
    selfview.value.muted = true;
    addLocalTracks(caller.value[data.from_id]);

    if (caller.value[data.from_id].remoteDescription.type !== 'offer') {
        return;
    }
    await createAnswer(caller.value[data.from_id], data.from_id);
}

const addLocalTracks = (rtcPeerConnection) => {
    localUserMedia.value.getTracks().forEach((track) => {
        rtcPeerConnection.addTrack(track, localUserMedia.value);
    })
    console.log("Local tracks added")
}

const createAnswer = async(rtcPeerConnection, remotePeerId) => {
    let answer;
    try {
        answer = await rtcPeerConnection.createAnswer();
        await rtcPeerConnection.setLocalDescription(answer);
    } catch (error) {
        console.error(error)
    }
    messagesChannel.value.trigger("client-answer", {
        tutorat_id: messengerStore.activeTutoratId, // Messenger
        sdp: answer,
        from_id: remotePeerId, // Me
    });
}

const createOffer = async(rtcPeerConnection, remotePeerId) => {
    let offer;
    try {
        offer = await rtcPeerConnection.createOffer();
        await rtcPeerConnection.setLocalDescription(offer);
    } catch (error) {
        console.error(error)
    }

    messagesChannel.value.trigger("client-sdp", {
        tutorat_id: messengerStore.activeTutoratId, // Messenger
        sdp: offer,
        from_id: remotePeerId, // Me
    });
}

const toggleMic = () => {
    micActive.value = !micActive.value;
    toggleMediaStream('audio', micActive.value);
}

const toggleCamera = () => {
    cameraActive.value = !cameraActive.value;
    toggleMediaStream('video', cameraActive.value);
}

// type: 'video' | 'audio', state: boolean
const toggleMediaStream = (type, state) => {
    selfview.value?.srcObject.getTracks()
        .forEach((track) => {
            if (track.kind === type) {
                track.enabled = state;
            }
        })
}

const leaveRoom = () => {

    if (selfview.value?.srcObject) {
        selfview.value?.srcObject.getTracks()
            .forEach((track) => track.stop()); // Stops sending all tracks of User.
    }
    if (remoteview.value?.srcObject) {
        remoteview.value?.srcObject.getTracks()
            .forEach((track) => track.stop()); // Stops receiving all tracks from Peer.
    }

    endCurrentCall();

    // Checks if there is peer on the other side and safely closes the existing connection established with the peer.
    /*if (caller.value[currentUser]) {
        caller.value[currentUser].ontrack = null;
        caller.value[currentUser].onicecandidate = null;
        caller.value[currentUser].close();
        caller.value[currentUser] = null;
    }*/
}

const startShareScreen = async() => {
    try {
        if (localScreen.value.srcObject) {
            return stopShareScreen();
        }

        const mediaStream = await navigator.mediaDevices.getDisplayMedia({
            video: {
                cursor: "always"
            },
            audio: false
        });

        shareScreenActive.value = !shareScreenActive.value;
        shareScreen.value = mediaStream;
        localScreen.value.srcObject = mediaStream;
        mediaStream.getVideoTracks().forEach(track => {
            caller.value.addTrack(track, mediaStream);
        });

        const localStream = await  caller.value.getLocalStreams();
        console.log('localScreen =>', localStream)
        messagesChannel.value.trigger("client-share-screen", {
            tutorat_id: messengerStore.activeTutoratId, // Messenger
            mediaStream: localStream,
            from_id: authStore.user?.id, // Me
        });

        mediaStream.getVideoTracks()[0].addEventListener('ended', () => {
            stopShareScreen();
        });
    } catch (ex) {
        console.log("Error occurred", ex);
    }
}

const stopShareScreen = () => {
    const tracks = localScreen.value.srcObject.getVideoTracks();
    tracks.forEach((track) => track.stop());
    localScreen.value.srcObject = null;
    shareScreenActive.value = !shareScreenActive.value;
    messagesChannel.value.trigger("remove-share-screen", {
        tutorat_id: messengerStore.activeTutoratId, // Messenger
        id: shareScreen.value?.id,
        from_id: authStore.user?.id, // Me
    });
}


///////////////////////////////////////////
/*
const rtcConnection = ref(null);
const userStream = ref();
const userVideo = ref(null);
const partnerVideo = ref(null);
const channelRef = ref(null);
const host = ref(false)

watchEffect(() => {
    channelRef.value = $pusher.subscribe(`presence-${messengerStore.activeTutoratId}`);
    // when a users subscribe
    channelRef.value.bind(
        'pusher:subscription_succeeded',
        (members) => {
            if (members.count === 1) {
                // when subscribing, if you are the first member, you are the host
                host.value = true
            }

            // example only supports 2 users per call
            if (members.count > 2) {
                // 3+ person joining will get sent back home
                // Can handle this however you'd like
                // router.push('/')
                console.log('members =>', members);
            }
            handleRoomJoined()
        }
    );

    // when a member leaves the chat
    channelRef.value.bind('pusher:member_removed', () => {
        handlePeerLeaving()
    });

    channelRef.value.bind(
        'client-offer',
        (offer) => {
            // offer is sent by the host, so only non-host should handle it
            if (!host.value) {
                handleReceivedOffer(offer)
            }
        }
    );

    // When the second peer tells host they are ready to start the call
    // This happens after the second peer has grabbed their media
    channelRef.value.bind('client-ready', () => {
        initiateCall()
    });

    channelRef.value.bind(
        'client-answer',
        (answer) => {
            // answer is sent by non-host, so only host should handle it
            if (host.value) {
                handleAnswerReceived(answer)
            }
        }
    );

    channelRef.value.bind(
        'client-ice-candidate',
        (iceCandidate) => {
            // answer is sent by non-host, so only host should handle it
            handlerNewIceCandidateMsg(iceCandidate)
        }
    );
});
const createPeerConnection = () => {
    // We create a RTC Peer Connection
    const connection = new RTCPeerConnection(ICE_SERVERS)

    // We implement our onicecandidate method for when we received a ICE candidate from the STUN server
    connection.onicecandidate = handleICECandidateEvent

    // We implement our onTrack method for when we receive tracks
    connection.ontrack = handleTrackEvent
    connection.onicecandidateerror = (e) => console.log(e)
    return connection
}

const handleRoomJoined = async () => {
    // const camera = await navigator.mediaDevices.getUserMedia({video: true, audio: true});
    // console.log('camera =>', camera)
    navigator.mediaDevices.getUserMedia({
        audio: true,
        video: true // { width: 1280, height: 720 },
    })
        .then((stream) => {
            console.log('stream =>', stream)
            // store reference to the stream and provide it to the video element
            userStream.current = stream
            userVideo.value = {
                srcObject: stream
            }
            console.log('userVideo =>', userVideo)
            userVideo.value.onloadedmetadata = () => {
                userVideo.value.play()
            }
            if (!host.value) {
                // the 2nd peer joining will tell to host they are ready
                channelRef.value?.trigger('client-ready', {})
            }
        })
        .catch((err) => {
            // handle the error
            console.log(err)
        })
}

const initiateCall = () => {
    if (host.value) {
        rtcConnection.value = createPeerConnection()
        // Host creates offer
        userStream.value?.getTracks().forEach((track) => {
            rtcConnection.value?.addTrack(track, userStream.value);
        });
        rtcConnection
            .value?.createOffer()
            .then((offer) => {
                rtcConnection.value?.setLocalDescription(offer)
                // 4. Send offer to other peer via pusher
                // Note: 'client-' prefix means this event is not being sent directly from the client
                // This options needs to be turned on in Pusher app settings
                channelRef.value?.trigger('client-offer', offer)
            })
            .catch((error) => {
                console.log(error)
            })
    }
}

const handleReceivedOffer = (offer) => {
    rtcConnection.value = createPeerConnection()
    userStream.value?.getTracks().forEach((track) => {
        // Adding tracks to the RTCPeerConnection to give peer access to it
        rtcConnection.value?.addTrack(track, userStream.value)
    })

    rtcConnection.value.setRemoteDescription(offer)
    rtcConnection
        .value.createAnswer()
        .then((answer) => {
            rtcConnection.value?.setLocalDescription(answer)
            channelRef.value?.trigger('client-answer', answer)
        })
        .catch((error) => {
            console.log(error)
        })

}
const handleAnswerReceived = (answer) => {
    rtcConnection
        .value?.setRemoteDescription(answer)
        .catch((error) => console.log(error))
}

const handleICECandidateEvent = async (event) => {
    if (event.candidate) {
        // return sentToPusher('ice-candidate', event.candidate)
        channelRef.value?.trigger('client-ice-candidate', event.candidate)
    }
}

const handlerNewIceCandidateMsg = (incoming) => {
    // We cast the incoming candidate to RTCIceCandidate
    const candidate = new RTCIceCandidate(incoming)
    rtcConnection
        .value?.addIceCandidate(candidate)
        .catch((error) => console.log(error))
}

const handleTrackEvent = (event) => {
    partnerVideo.value = {
        srcObject: event.streams[0]
    }
}

const handlePeerLeaving = () => {
    host.value = true
    if (partnerVideo.value?.srcObject) {
        partnerVideo.value.srcObject
            .getTracks()
            .forEach((track) => track.stop()) // Stops receiving all track of Peer.
    }

    // Safely closes the existing connection established with the peer who left.
    if (rtcConnection.value) {
        rtcConnection.value.ontrack = null
        rtcConnection.value.onicecandidate = null
        rtcConnection.value.close()
        rtcConnection.value = null
    }
}*/
</script>

<template>
    <div class="h-full flex flex-col overflow-hidden">
        <Top />
        <div :class="[{'gap-4 flex flex-row-reverse': isActiveVideo}]">
            <div class="basis-1/4">
                <div ref="messagesContainer" class="grow px-5 py-5 flex flex-col overflow-y-auto h-[calc(100vh_-_15rem)] scrollbar">
                    <Middle
                        :active-conversation="activeConversation"
                        @select-message-to-reply-to="selectMessageToReplyTo"
                        @download-attachment="downloadAttachment"
                    />
                    <TypeIndicator />
                </div>
                <Bottom
                    :active-conversation="activeConversation"
                    :selected-message-to-reply-to="selectedMessageToReplyTo"
                    @remove-message-to-reply-to="removeMessageToReplyTo"
                    @scroll-bottom="scrollToBottom"
                    @typing="isTyping"
                />
            </div>
            <div :class="{'hidden': !isActiveVideo}" class="h-[calc(100vh_-_12rem)] basis-3/4 bg-black rounded-2xl relative">
                <div class="flex justify-center items-end w-full space-x-4 absolute h-10 bottom-12">
                    <div class="z-999 bg-gray-400 rounded-2xl flex justify-center justify-items-center items-center self-center py-1.5 px-2">
                        <BaseIcon
                            :path="micActive ? mdiMicrophone : mdiMicrophoneOff"
                            class="flex-none text-white cursor-pointer z-999 justify-center items-center"
                            w="w-12"
                            h="h-12"
                            size="30"
                            @click="toggleMic"
                        />
                        <BaseIcon
                            :path="mdiPhoneHangup"
                            class="flex-none text-red-500 cursor-pointer z-999 justify-center items-center"
                            w="w-12"
                            h="h-12"
                            size="30"
                            @click="leaveRoom"
                        />
                        <BaseIcon
                            :path="cameraActive ? mdiVideo : mdiVideoOff"
                            class="flex-none text-white cursor-pointer z-999 justify-center items-center"
                            w="w-12"
                            h="h-12"
                            size="30"
                            @click="toggleCamera"
                        />
                        <BaseIcon
                            :path="shareScreenActive ? mdiCloseBox : mdiArrowUpBox"
                            class="flex-none text-white cursor-pointer z-999 justify-center items-center"
                            w="w-12"
                            h="h-12"
                            size="30"
                            @click="startShareScreen"
                        />
                    </div>
                </div>
                <div class="w-40 h-40 p-0 m-2 z-100 absolute bottom-0 right-0 rounded-2xl border-gray-400 border-solid flex items-center justify-center bg-black">
                    <BaseIcon
                        :path="micActive ? mdiMicrophone : mdiMicrophoneOff"
                        class="flex-none text-white cursor-pointer z-999 absolute justify-start items-end"
                        w="w-full"
                        h="h-full"
                        size="30"
                        @click="toggleMic"
                    />
                    <span v-show="!cameraActive" class="p-2 rounded-full bg-gray-400">AL</span>
                    <video v-show="cameraActive" ref="selfview" class="w-full h-full p-0 rounded-2xl border-solid" autoplay></video>
                </div>
                <div class="absolute w-full h-full top-0 flex flex-wrap grid gap-1 z-20 wrapper">
                    <div v-show="shareScreenActive">
                        <video ref="localScreen" autoplay ></video>
                    </div>
                    <video ref="remoteview" autoplay></video>
                    <div id="video-container">
                        <div class="text-red-500 bg-green-100" v-for="n in 6">{{ n }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.scrollbar::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
}
.scrollbar::-webkit-scrollbar {
    width: 8px;
    background-color: #F5F5F5;
}
.scrollbar::-webkit-scrollbar-thumb {
    border: 2px solid #F5F5F5;
    background-color: #000000;
}

#list ul {
    list-style: none;
}

#list ul li {
    font-family: Georgia, serif, Times;
    font-size: 18px;
    display: block;
    width: 300px;
    height: 28px;
    background-color: #333;
    border-left: 5px solid #222;
    border-right: 5px solid #222;
    padding-left: 10px;
    text-decoration: none;
    color: #bfe1f1;
}

#list ul li:hover {
    box-shadow: 10px 10px 20px #000000;
}
.wrapper {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    padding: 10px;
    grid-auto-columns: auto;
    gap: 10px;
}
.wrapper div {
    background-color: rgba(255, 255, 255, 0.8);
    text-align: center;
    padding: 20px 0;
    font-size: 30px;
    width: 200px;
    height: 200px;
}
</style>
