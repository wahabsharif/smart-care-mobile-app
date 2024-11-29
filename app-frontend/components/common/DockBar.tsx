import React from "react";
import { Tabs } from "expo-router";
import { Platform } from "react-native";

// Components
import { HapticTab } from "@/components/HapticTab";
import { Colors } from "@/constants/Colors";
import { useColorScheme } from "@/hooks/useColorScheme";

// Icons
import { Ionicons } from "@expo/vector-icons";
import FontAwesome from "@expo/vector-icons/FontAwesome";
import MaterialCommunityIcons from "@expo/vector-icons/MaterialCommunityIcons";

export default function DockBar() {
  const colorScheme = useColorScheme();

  return (
    <Tabs
      screenOptions={{
        tabBarActiveTintColor: Colors[colorScheme ?? "light"].tint,
        headerShown: false,
        tabBarButton: HapticTab,
        tabBarLabelStyle: {
          fontSize: 15,
          marginTop: 2,
        },
        tabBarIconStyle: {
          marginBottom: 1,
        },
        tabBarStyle: Platform.select({
          default: {
            backgroundColor: Colors[colorScheme ?? "light"].background,
            height: 60, // Default height
            paddingBottom: 10, // Default padding
          },
        }),
      }}
    >
      {/* Chats Screen */}
      <Tabs.Screen
        name="index"
        options={{
          title: "Home",
          tabBarIcon: ({ color }) => (
            <Ionicons name="chatbubbles" size={28} color={color} />
          ),
        }}
      />

      {/* Groups Screen */}
      <Tabs.Screen
        name="groups"
        options={{
          title: "Groups",
          tabBarIcon: ({ color }) => (
            <FontAwesome name="group" size={28} color={color} />
          ),
        }}
      />

      {/* Broadcast Screen */}
      <Tabs.Screen
        name="broadcast"
        options={{
          title: "Broadcast",
          tabBarIcon: ({ color }) => (
            <MaterialCommunityIcons name="broadcast" size={28} color={color} />
          ),
        }}
      />
    </Tabs>
  );
}
