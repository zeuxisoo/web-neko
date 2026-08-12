<script setup lang="ts">
import api from '@/api';
import { AccountProfileResponse, AccountProfileUpdatePayload, MeResponse } from '@/api/types';
import AvatarUpload from '@/components/avatar-upload/AvatarUpload.vue';
import { Button } from '@/components/base/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/base/card';
import { Input } from '@/components/base/input';
import { Label } from '@/components/base/label';
import { useAuthStore, useUserStore } from '@/stores';
import { WhoopsHandler } from '@/utils';
import validator from '@/validators';
import { Loader } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';
import LightBox, { LightBoxComponent } from 'vue-it-bigger';
import { useRouter } from 'vue-router';
import { toast } from 'vue-sonner';

const user = ref<MeResponse['data']>({} as MeResponse['data']);
const isLoading = ref(false);
const showLightBox = ref(false);
const lightBoxRef = ref<LightBoxComponent>();

const lightboxMedia = computed(() => [
    {
        type: 'image',
        src: user.value.link_thumb,
        thumb: user.value.link_cover,
        caption: user.value.description,
    },
]);

const handleShowLightBox = (index: number) => {
    if (!user.value.avatar) {
        return;
    }

    if (lightBoxRef.value) {
        lightBoxRef.value.showImage(index);
    }
};

const router = useRouter();
const userStore = useUserStore();

onMounted(async () => {
    try {
        isLoading.value = true;

        const me = await userStore.fetch();

        user.value.username = me.username;
        user.value.email = me.email;
        user.value.description = me.description;
        user.value.avatar = me.avatar;
        user.value.link_cover = me.link_cover;
        user.value.link_thumb = me.link_thumb;
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch me action in account profile');
    } finally {
        isLoading.value = false;
    }
});

const handleAccountAvatarSave = async (file: File) => {
    const formData = new FormData();
    formData.append('file', file);

    try {
        isLoading.value = true;

        const { data, error } = await api.account.profile.updateAvatar(formData).json<MeResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const result = data.value.data;
            const avatar = result.avatar;
            const linkCover = result.link_cover;
            const linkThumb = result.link_thumb;

            userStore.setAvatar(avatar, linkCover, linkThumb);
            user.value.avatar = userStore.avatar;
            user.value.link_cover = userStore.link_cover;
            user.value.link_thumb = userStore.link_thumb;

            toast.info('Avatar updated');
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error on handle account avatar save action');
    } finally {
        isLoading.value = false;
    }
};

const handleAccountProfileSave = async () => {
    isLoading.value = true;

    try {
        const formData = validator.form('account.profile.update').validate({
            username: user.value?.username,
            email: user.value?.email,
            description: user.value?.description,
        });

        const { data, error } = await api.account.profile.update(formData as AccountProfileUpdatePayload).json<AccountProfileResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data.value && data.value.ok) {
            const result = data.value;

            const authStore = useAuthStore();
            authStore.deactivateAuth();

            router.push({
                name: 'index',
                replace: true,
            });

            toast.success(result.message);
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error on handle account profile save action');
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <div class="flex flex-col gap-2" v-if="user.link_cover">
        <Card>
            <CardHeader>
                <CardTitle>Profile</CardTitle>
                <CardDescription>Make changes to your profile here. After saving, you'll be logged out.</CardDescription>
            </CardHeader>
            <CardContent>
                <div class="grid gap-6">
                    <div class="grid gap-3">
                        <Label for="tabs-avatar">Avatar</Label>
                        <Loader class="animate-spin" v-if="isLoading" />
                        <AvatarUpload
                            :url="user.link_cover"
                            :name="user.username"
                            @change="handleAccountAvatarSave"
                            @preview="handleShowLightBox(0)"
                            v-else
                        />
                    </div>
                </div>
            </CardContent>
        </Card>
        <Card>
            <CardContent class="grid gap-6">
                <div class="grid gap-3">
                    <Label for="tabs-name">Username</Label>
                    <Input id="tabs-name" placeholder="meow" v-model="user.username" />
                </div>
                <div class="grid gap-3">
                    <Label for="tabs-username">Email</Label>
                    <Input id="tabs-username" placeholder="meow@home.local" v-model="user.email" />
                </div>
                <div class="grid gap-3">
                    <Label for="tabs-description">Description</Label>
                    <Input id="tabs-description" placeholder="meow meow ~ meow ~~" v-model="user.description" />
                </div>
            </CardContent>
            <CardFooter>
                <Button @click="handleAccountProfileSave">
                    <Loader class="animate-spin" v-if="isLoading" />
                    <template v-else>Save</template>
                </Button>
            </CardFooter>
        </Card>
    </div>
    <LightBox ref="lightBoxRef" :media="lightboxMedia" :showLightBox="showLightBox" :interfaceHideTime="86400" :showCaption="true" />
</template>
