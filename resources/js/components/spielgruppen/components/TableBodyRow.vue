<template>
    <div class="main-row">
        <div class="table-row">
            <div class="td" style="width: 25%;">
                <img v-if="spielgruppen" :src="spielgruppen.file_url" style="height:40px; width:40px"/>
            </div>
            <div class="td" style="width: 30%;">
                <router-link class="td" :to="`/spielgruppen/show/${spielgruppen.id}`" style=" text-decoration: none;">
                {{spielgruppen.name}}
                </router-link>
            </div>
            <div class="td" style="width: 35%;">{{spielgruppen.street_name}} {{spielgruppen.street_number}}, {{spielgruppen.postal_code}} {{spielgruppen.location}}</div>
            <div class="td" style="width: 5%;">
                <Status :status="spielgruppen.status_id"/>
            </div>
            <div class="td" style="width: 5%;">
                <div class="menu-bar" @click="menubar_is_opened = !menubar_is_opened"
                    v-click-outside="() => menubar_is_opened = false">
                    <MenuIcon style="cursor: pointer" :color="menubar_is_opened ? '#4C85A7' : ''" />
                    <div class="options" v-if="menubar_is_opened" @click="menubar_is_opened = false">
                        <button class="option" v-if="(spielgruppen.status_id != 2) && (role == 'admin' || role=='super_admin')" @click="updateStatus(2)">
                            Deaktivieren
                        </button>
                        <button class="option" v-if="spielgruppen.status_id == 2" @click="updateStatus(1)">
                            Aktivieren
                        </button>
                        <router-link class="option" :to="`/spielgruppen/edit/${spielgruppen.id}`" v-if="role == 'admin' || role=='super_admin'">Bearbeiten</router-link>
                        <router-link class="option" :to="`/spielgruppen/show/${spielgruppen.id}`">Details ansehen</router-link>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-row-main" style="cursor:pointer;border-bottom: 1px solid #D3E3EC;">
            <div class="table-row-new" @click="showData()">
                <div class="td" style="width: 25%; color:#E97D32;"><DownArrowIcon v-if="!menubar_is_opened_by_id"/><UpArrowIcon v-else/> Öffnungszeiten</div>
                <div class="td" style="width: 15%;">Montag</div>
                <div class="td" style="width: 15%;">Dienstag</div>
                <div class="td" style="width: 15%;">Mittwoch</div>
                <div class="td" style="width: 15%;">Donnerstag</div>
                <div class="td" style="width: 15%;">Freitag</div>
            </div>
            <div class="table-row-new" v-if="menubar_is_opened_by_id">
                <div class="td" style="width: 25%;"><p style="height:20px">{{spielgruppen.morning_time_range}}</p><p style="height:20px">{{spielgruppen.afternoon_time_range}}</p></div>
                <div class="td" style="width: 15%; display: flex; flex-direction: column; gap: 15px">
                    <div>
                        <div class="marked" v-if="isAvailable(spielgruppen.opening_hours, 1, 1)">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.8839 8.88388L17.7678 8L16 6.23223L15.1161 7.11612L16.8839 8.88388ZM8.99998 15L8.1161 15.8839C8.60426 16.3721 9.39571 16.3721 9.88386 15.8839L8.99998 15ZM6.88382 11.1161L5.99993 10.2322L4.23217 12L5.11606 12.8839L6.88382 11.1161ZM15.1161 7.11612L8.1161 14.1161L9.88386 15.8839L16.8839 8.88388L15.1161 7.11612ZM5.11606 12.8839L8.1161 15.8839L9.88386 14.1161L6.88382 11.1161L5.11606 12.8839ZM18.75 11C18.75 15.2802 15.2802 18.75 11 18.75V21.25C16.6609 21.25 21.25 16.6609 21.25 11H18.75ZM11 18.75C6.71979 18.75 3.25 15.2802 3.25 11H0.75C0.75 16.6609 5.33908 21.25 11 21.25V18.75ZM3.25 11C3.25 6.71979 6.71979 3.25 11 3.25V0.75C5.33908 0.75 0.75 5.33908 0.75 11H3.25ZM11 3.25C15.2802 3.25 18.75 6.71979 18.75 11H21.25C21.25 5.33908 16.6609 0.75 11 0.75V3.25Z" fill="#E97D32"/>
                            </svg>
                            <div class="letsplan-tooltip">
                                <ExclamatoryIcon />
                                <div class="tooltip-text">
                                    <div class="flex gap-4"><span>Leiter/in: </span><span class="ml-4">{{getLead(spielgruppen.opening_hours, 1, 1)}}</span></div>
                                    <div class="flex gap-3"><span>Assistent/in: </span> <span>{{getAssistant(spielgruppen.opening_hours, 1, 1)}}</span></div>
                                </div>
                            </div>
                        </div>
                        <MinusIcon v-else/>
                    </div>
                    <div>
                        <div class="marked" v-if="isAvailable(spielgruppen.opening_hours, 1, 2)">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.8839 8.88388L17.7678 8L16 6.23223L15.1161 7.11612L16.8839 8.88388ZM8.99998 15L8.1161 15.8839C8.60426 16.3721 9.39571 16.3721 9.88386 15.8839L8.99998 15ZM6.88382 11.1161L5.99993 10.2322L4.23217 12L5.11606 12.8839L6.88382 11.1161ZM15.1161 7.11612L8.1161 14.1161L9.88386 15.8839L16.8839 8.88388L15.1161 7.11612ZM5.11606 12.8839L8.1161 15.8839L9.88386 14.1161L6.88382 11.1161L5.11606 12.8839ZM18.75 11C18.75 15.2802 15.2802 18.75 11 18.75V21.25C16.6609 21.25 21.25 16.6609 21.25 11H18.75ZM11 18.75C6.71979 18.75 3.25 15.2802 3.25 11H0.75C0.75 16.6609 5.33908 21.25 11 21.25V18.75ZM3.25 11C3.25 6.71979 6.71979 3.25 11 3.25V0.75C5.33908 0.75 0.75 5.33908 0.75 11H3.25ZM11 3.25C15.2802 3.25 18.75 6.71979 18.75 11H21.25C21.25 5.33908 16.6609 0.75 11 0.75V3.25Z" fill="#E97D32"/>
                            </svg>
                            <div class="letsplan-tooltip">
                                <ExclamatoryIcon />
                                <div class="tooltip-text">
                                    <div class="flex gap-4"><span>Leiter/in: </span><span class="ml-4">{{getLead(spielgruppen.opening_hours, 1, 2)}}</span></div>
                                    <div class="flex gap-3"><span>Assistent/in: </span> <span>{{getAssistant(spielgruppen.opening_hours, 1, 2)}}</span></div>
                                </div>
                            </div>
                        </div>
                        <MinusIcon v-else/>
                    </div>
                </div>
                <div class="td" style="width: 15%; display: flex; flex-direction: column; gap: 15px">
                    <div>
                        <div class="marked" v-if="isAvailable(spielgruppen.opening_hours, 2, 1)">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.8839 8.88388L17.7678 8L16 6.23223L15.1161 7.11612L16.8839 8.88388ZM8.99998 15L8.1161 15.8839C8.60426 16.3721 9.39571 16.3721 9.88386 15.8839L8.99998 15ZM6.88382 11.1161L5.99993 10.2322L4.23217 12L5.11606 12.8839L6.88382 11.1161ZM15.1161 7.11612L8.1161 14.1161L9.88386 15.8839L16.8839 8.88388L15.1161 7.11612ZM5.11606 12.8839L8.1161 15.8839L9.88386 14.1161L6.88382 11.1161L5.11606 12.8839ZM18.75 11C18.75 15.2802 15.2802 18.75 11 18.75V21.25C16.6609 21.25 21.25 16.6609 21.25 11H18.75ZM11 18.75C6.71979 18.75 3.25 15.2802 3.25 11H0.75C0.75 16.6609 5.33908 21.25 11 21.25V18.75ZM3.25 11C3.25 6.71979 6.71979 3.25 11 3.25V0.75C5.33908 0.75 0.75 5.33908 0.75 11H3.25ZM11 3.25C15.2802 3.25 18.75 6.71979 18.75 11H21.25C21.25 5.33908 16.6609 0.75 11 0.75V3.25Z" fill="#E97D32"/>
                            </svg>
                            <div class="letsplan-tooltip">
                                <ExclamatoryIcon />
                                <div class="tooltip-text">
                                    <div class="flex gap-4"><span>Leiter/in: </span><span class="ml-4">{{getLead(spielgruppen.opening_hours, 2, 1)}}</span></div>
                                    <div class="flex gap-3"><span>Assistent/in: </span> <span>{{getAssistant(spielgruppen.opening_hours, 2, 1)}}</span></div>
                                </div>
                            </div>
                        </div>
                        <MinusIcon v-else/>
                    </div>
                    <div>
                        <div class="marked" v-if="isAvailable(spielgruppen.opening_hours, 2, 2)">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.8839 8.88388L17.7678 8L16 6.23223L15.1161 7.11612L16.8839 8.88388ZM8.99998 15L8.1161 15.8839C8.60426 16.3721 9.39571 16.3721 9.88386 15.8839L8.99998 15ZM6.88382 11.1161L5.99993 10.2322L4.23217 12L5.11606 12.8839L6.88382 11.1161ZM15.1161 7.11612L8.1161 14.1161L9.88386 15.8839L16.8839 8.88388L15.1161 7.11612ZM5.11606 12.8839L8.1161 15.8839L9.88386 14.1161L6.88382 11.1161L5.11606 12.8839ZM18.75 11C18.75 15.2802 15.2802 18.75 11 18.75V21.25C16.6609 21.25 21.25 16.6609 21.25 11H18.75ZM11 18.75C6.71979 18.75 3.25 15.2802 3.25 11H0.75C0.75 16.6609 5.33908 21.25 11 21.25V18.75ZM3.25 11C3.25 6.71979 6.71979 3.25 11 3.25V0.75C5.33908 0.75 0.75 5.33908 0.75 11H3.25ZM11 3.25C15.2802 3.25 18.75 6.71979 18.75 11H21.25C21.25 5.33908 16.6609 0.75 11 0.75V3.25Z" fill="#E97D32"/>
                            </svg>
                            <div class="letsplan-tooltip">
                                <ExclamatoryIcon />
                                <div class="tooltip-text">
                                    <div class="flex gap-4"><span>Leiter/in: </span><span class="ml-4">{{getLead(spielgruppen.opening_hours, 2, 2)}}</span></div>
                                    <div class="flex gap-3"><span>Assistent/in: </span> <span>{{getAssistant(spielgruppen.opening_hours, 2, 2)}}</span></div>
                                </div>
                            </div>
                        </div>
                        <MinusIcon v-else/>
                    </div>
                </div>
                <div class="td" style="width: 15%; display: flex; flex-direction: column; gap: 15px">
                    <div>
                        <div class="marked" v-if="isAvailable(spielgruppen.opening_hours, 3, 1)">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.8839 8.88388L17.7678 8L16 6.23223L15.1161 7.11612L16.8839 8.88388ZM8.99998 15L8.1161 15.8839C8.60426 16.3721 9.39571 16.3721 9.88386 15.8839L8.99998 15ZM6.88382 11.1161L5.99993 10.2322L4.23217 12L5.11606 12.8839L6.88382 11.1161ZM15.1161 7.11612L8.1161 14.1161L9.88386 15.8839L16.8839 8.88388L15.1161 7.11612ZM5.11606 12.8839L8.1161 15.8839L9.88386 14.1161L6.88382 11.1161L5.11606 12.8839ZM18.75 11C18.75 15.2802 15.2802 18.75 11 18.75V21.25C16.6609 21.25 21.25 16.6609 21.25 11H18.75ZM11 18.75C6.71979 18.75 3.25 15.2802 3.25 11H0.75C0.75 16.6609 5.33908 21.25 11 21.25V18.75ZM3.25 11C3.25 6.71979 6.71979 3.25 11 3.25V0.75C5.33908 0.75 0.75 5.33908 0.75 11H3.25ZM11 3.25C15.2802 3.25 18.75 6.71979 18.75 11H21.25C21.25 5.33908 16.6609 0.75 11 0.75V3.25Z" fill="#E97D32"/>
                            </svg>
                            <div class="letsplan-tooltip">
                                <ExclamatoryIcon />
                                <div class="tooltip-text">
                                    <div class="flex gap-4"><span>Leiter/in: </span><span class="ml-4">{{getLead(spielgruppen.opening_hours, 3, 1)}}</span></div>
                                    <div class="flex gap-3"><span>Assistent/in: </span> <span>{{getAssistant(spielgruppen.opening_hours, 3, 1)}}</span></div>
                                </div>
                            </div>
                        </div>
                        <MinusIcon v-else/>
                    </div>
                    <div>
                        <div class="marked" v-if="isAvailable(spielgruppen.opening_hours, 3, 2)">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.8839 8.88388L17.7678 8L16 6.23223L15.1161 7.11612L16.8839 8.88388ZM8.99998 15L8.1161 15.8839C8.60426 16.3721 9.39571 16.3721 9.88386 15.8839L8.99998 15ZM6.88382 11.1161L5.99993 10.2322L4.23217 12L5.11606 12.8839L6.88382 11.1161ZM15.1161 7.11612L8.1161 14.1161L9.88386 15.8839L16.8839 8.88388L15.1161 7.11612ZM5.11606 12.8839L8.1161 15.8839L9.88386 14.1161L6.88382 11.1161L5.11606 12.8839ZM18.75 11C18.75 15.2802 15.2802 18.75 11 18.75V21.25C16.6609 21.25 21.25 16.6609 21.25 11H18.75ZM11 18.75C6.71979 18.75 3.25 15.2802 3.25 11H0.75C0.75 16.6609 5.33908 21.25 11 21.25V18.75ZM3.25 11C3.25 6.71979 6.71979 3.25 11 3.25V0.75C5.33908 0.75 0.75 5.33908 0.75 11H3.25ZM11 3.25C15.2802 3.25 18.75 6.71979 18.75 11H21.25C21.25 5.33908 16.6609 0.75 11 0.75V3.25Z" fill="#E97D32"/>
                            </svg>
                            <div class="letsplan-tooltip">
                                <ExclamatoryIcon />
                                <div class="tooltip-text">
                                    <div class="flex gap-4"><span>Leiter/in: </span><span class="ml-4">{{getLead(spielgruppen.opening_hours, 3, 2)}}</span></div>
                                    <div class="flex gap-3"><span>Assistent/in: </span> <span>{{getAssistant(spielgruppen.opening_hours, 3, 2)}}</span></div>
                                </div>
                            </div>
                        </div>
                        <MinusIcon v-else/>
                    </div>
                </div>
                <div class="td" style="width: 15%; display: flex; flex-direction: column; gap: 15px">
                    <div>
                        <div class="marked" v-if="isAvailable(spielgruppen.opening_hours, 4, 1)">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.8839 8.88388L17.7678 8L16 6.23223L15.1161 7.11612L16.8839 8.88388ZM8.99998 15L8.1161 15.8839C8.60426 16.3721 9.39571 16.3721 9.88386 15.8839L8.99998 15ZM6.88382 11.1161L5.99993 10.2322L4.23217 12L5.11606 12.8839L6.88382 11.1161ZM15.1161 7.11612L8.1161 14.1161L9.88386 15.8839L16.8839 8.88388L15.1161 7.11612ZM5.11606 12.8839L8.1161 15.8839L9.88386 14.1161L6.88382 11.1161L5.11606 12.8839ZM18.75 11C18.75 15.2802 15.2802 18.75 11 18.75V21.25C16.6609 21.25 21.25 16.6609 21.25 11H18.75ZM11 18.75C6.71979 18.75 3.25 15.2802 3.25 11H0.75C0.75 16.6609 5.33908 21.25 11 21.25V18.75ZM3.25 11C3.25 6.71979 6.71979 3.25 11 3.25V0.75C5.33908 0.75 0.75 5.33908 0.75 11H3.25ZM11 3.25C15.2802 3.25 18.75 6.71979 18.75 11H21.25C21.25 5.33908 16.6609 0.75 11 0.75V3.25Z" fill="#E97D32"/>
                            </svg>
                            <div class="letsplan-tooltip">
                                <ExclamatoryIcon />
                                <div class="tooltip-text">
                                    <div class="flex gap-4"><span>Leiter/in: </span><span class="ml-4">{{getLead(spielgruppen.opening_hours, 4, 1)}}</span></div>
                                    <div class="flex gap-3"><span>Assistent/in: </span> <span>{{getAssistant(spielgruppen.opening_hours, 4, 1)}}</span></div>
                                </div>
                            </div>
                        </div>
                        <MinusIcon v-else/>
                    </div>
                    <div>
                        <div class="marked" v-if="isAvailable(spielgruppen.opening_hours, 4, 2)">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.8839 8.88388L17.7678 8L16 6.23223L15.1161 7.11612L16.8839 8.88388ZM8.99998 15L8.1161 15.8839C8.60426 16.3721 9.39571 16.3721 9.88386 15.8839L8.99998 15ZM6.88382 11.1161L5.99993 10.2322L4.23217 12L5.11606 12.8839L6.88382 11.1161ZM15.1161 7.11612L8.1161 14.1161L9.88386 15.8839L16.8839 8.88388L15.1161 7.11612ZM5.11606 12.8839L8.1161 15.8839L9.88386 14.1161L6.88382 11.1161L5.11606 12.8839ZM18.75 11C18.75 15.2802 15.2802 18.75 11 18.75V21.25C16.6609 21.25 21.25 16.6609 21.25 11H18.75ZM11 18.75C6.71979 18.75 3.25 15.2802 3.25 11H0.75C0.75 16.6609 5.33908 21.25 11 21.25V18.75ZM3.25 11C3.25 6.71979 6.71979 3.25 11 3.25V0.75C5.33908 0.75 0.75 5.33908 0.75 11H3.25ZM11 3.25C15.2802 3.25 18.75 6.71979 18.75 11H21.25C21.25 5.33908 16.6609 0.75 11 0.75V3.25Z" fill="#E97D32"/>
                            </svg>
                            <div class="letsplan-tooltip">
                                <ExclamatoryIcon />
                                <div class="tooltip-text">
                                    <div class="flex gap-4"><span>Leiter/in: </span><span class="ml-4">{{getLead(spielgruppen.opening_hours, 4, 2)}}</span></div>
                                    <div class="flex gap-3"><span>Assistent/in: </span> <span>{{getAssistant(spielgruppen.opening_hours, 4, 2)}}</span></div>
                                </div>
                            </div>
                        </div>
                        <MinusIcon v-else/>
                    </div>
                </div>
                <div class="td" style="width: 15%; display: flex; flex-direction: column; gap: 15px">
                    <div>
                        <div class="marked" v-if="isAvailable(spielgruppen.opening_hours, 5, 1)">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.8839 8.88388L17.7678 8L16 6.23223L15.1161 7.11612L16.8839 8.88388ZM8.99998 15L8.1161 15.8839C8.60426 16.3721 9.39571 16.3721 9.88386 15.8839L8.99998 15ZM6.88382 11.1161L5.99993 10.2322L4.23217 12L5.11606 12.8839L6.88382 11.1161ZM15.1161 7.11612L8.1161 14.1161L9.88386 15.8839L16.8839 8.88388L15.1161 7.11612ZM5.11606 12.8839L8.1161 15.8839L9.88386 14.1161L6.88382 11.1161L5.11606 12.8839ZM18.75 11C18.75 15.2802 15.2802 18.75 11 18.75V21.25C16.6609 21.25 21.25 16.6609 21.25 11H18.75ZM11 18.75C6.71979 18.75 3.25 15.2802 3.25 11H0.75C0.75 16.6609 5.33908 21.25 11 21.25V18.75ZM3.25 11C3.25 6.71979 6.71979 3.25 11 3.25V0.75C5.33908 0.75 0.75 5.33908 0.75 11H3.25ZM11 3.25C15.2802 3.25 18.75 6.71979 18.75 11H21.25C21.25 5.33908 16.6609 0.75 11 0.75V3.25Z" fill="#E97D32"/>
                            </svg>
                            <div class="letsplan-tooltip">
                                <ExclamatoryIcon />
                                <div class="tooltip-text">
                                    <div class="flex gap-4"><span>Leiter/in: </span><span class="ml-4">{{getLead(spielgruppen.opening_hours, 5, 1)}}</span></div>
                                    <div class="flex gap-3"><span>Assistent/in: </span> <span>{{getAssistant(spielgruppen.opening_hours, 5, 1)}}</span></div>
                                </div>
                            </div>
                        </div>
                        <MinusIcon v-else/>
                    </div>
                    <div>
                        <div class="marked" v-if="isAvailable(spielgruppen.opening_hours, 5, 2)">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.8839 8.88388L17.7678 8L16 6.23223L15.1161 7.11612L16.8839 8.88388ZM8.99998 15L8.1161 15.8839C8.60426 16.3721 9.39571 16.3721 9.88386 15.8839L8.99998 15ZM6.88382 11.1161L5.99993 10.2322L4.23217 12L5.11606 12.8839L6.88382 11.1161ZM15.1161 7.11612L8.1161 14.1161L9.88386 15.8839L16.8839 8.88388L15.1161 7.11612ZM5.11606 12.8839L8.1161 15.8839L9.88386 14.1161L6.88382 11.1161L5.11606 12.8839ZM18.75 11C18.75 15.2802 15.2802 18.75 11 18.75V21.25C16.6609 21.25 21.25 16.6609 21.25 11H18.75ZM11 18.75C6.71979 18.75 3.25 15.2802 3.25 11H0.75C0.75 16.6609 5.33908 21.25 11 21.25V18.75ZM3.25 11C3.25 6.71979 6.71979 3.25 11 3.25V0.75C5.33908 0.75 0.75 5.33908 0.75 11H3.25ZM11 3.25C15.2802 3.25 18.75 6.71979 18.75 11H21.25C21.25 5.33908 16.6609 0.75 11 0.75V3.25Z" fill="#E97D32"/>
                            </svg>
                            <div class="letsplan-tooltip">
                                <ExclamatoryIcon />
                                <div class="tooltip-text">
                                    <div class="flex gap-4"><span>Leiter/in: </span><span class="ml-4">{{getLead(spielgruppen.opening_hours, 5, 2)}}</span></div>
                                    <div class="flex gap-3"><span>Assistent/in: </span> <span>{{getAssistant(spielgruppen.opening_hours, 5, 2)}}</span></div>
                                </div>
                            </div>
                        </div>
                        <MinusIcon v-else/>
                    </div>
                </div>
            </div>
        </div>
        <ConfirmationModal />
        <NotificationModal />
    </div>
</template>
<script >
import ExclamatoryIcon from './ExclamatoryIcon.vue';
import MenuIcon from '../MenuIcon.vue';
import Status from './Status.vue';
import DownArrowIcon from "./DownArrowIcon.vue";
import UpArrowIcon from "./UpArrowIcon.vue";
import MinusIcon from "./MinusIcon.vue"
import ConfirmationModal from "./ConfirmationModal.vue";
import NotificationModal from "./Notification.vue";
import axios from "../../../axios"
export default {
    props: {
        spielgruppen: {
            type: Object,
            required: true,
        },
        role:{
            type: String,
            default: ''
        }
    },

    data() {
        return {
            menubar_is_opened: false,
            menubar_is_opened_by_id: false
        }
    },
    methods:{
        updateStatus(status) {
            const title = status == 1 ? "Spielgruppe aktivieren?" : "Spielgruppe deaktivieren?"
            const description = status == 1 ?
            `Sind Sie sicher, dass Sie die Spielgruppe “${this.spielgruppen.name}” wirklich aktivieren wollen?`
                : `Sind Sie sicher, dass Sie die Spielgruppe “${this.spielgruppen.name}” wirklich deaktivieren wollen?`

            EventBus.$emit('confirmation-modal:show', {
                title,
                description,
                noClick: () => EventBus.$emit('confirmation-modal:hide'),
                yesClick: () => {
                    EventBus.$emit('confirmation-modal:hide')
                    axios.put(`/api/spielgruppen/${this.spielgruppen.id}/update-status`, {
                        status,
                    }).then(res => {
                        EventBus.$emit('notification-modal:show', res.data.message)
                        this.$emit('updateResult')
                    })
                },
            })

        },
        isAvailable(opening_hours, day_id, hour_id){
            var flag = 0;
            opening_hours.forEach(item => {
                if(item.day_id == day_id && item.hour_id == hour_id){
                    flag = 1;
                    return;
                }
            })

            if(flag == 1){
                return true
            }
            return false
        },
        showData(){
            this.menubar_is_opened_by_id = this.menubar_is_opened_by_id == false ? true : false
        },
        getLead(opening_hours, day_id, hour_id){
            var name = '';
            opening_hours.forEach(item => {
                if(item.day_id == day_id && item.hour_id == hour_id){
                    name = item.lead?.first_name+' '+item.lead?.last_name;
                    return
                }
            })
            return name
        },

        getAssistant(opening_hours, day_id, hour_id){
            var name = '';
            opening_hours.forEach(item => {
                if(item.day_id == day_id && item.hour_id == hour_id){
                    name = item.assistant?.first_name+' '+item.assistant?.last_name;
                    return
                }
            })
            return name
        },

    },

    components: { MenuIcon, Status, DownArrowIcon, MinusIcon, ConfirmationModal, NotificationModal, UpArrowIcon, ExclamatoryIcon }
}
</script>

<style lang="scss">

.table-row-new {
    min-height: 56px;
    display: flex;
    align-items: center;
    padding: 0px 22px 0px 25px;
    .td {
        font-family: 'Nexa';
        font-style: normal;
        font-weight: 400;
        font-size: 18px;
        line-height: 27px;
        color: #2C5B7D;
    }

    .marked {
        display: flex;
        gap: 10px;

        .letsplan-tooltip {
            display: flex;
            position: relative;
            align-items: flex-start;

            .tooltip-text {
                display: none;
                position: absolute;
                top: -10%;
                right: -5px;
                padding: 22px 16px 16px 15px;
                background-color: white;
                width: 300px;

                font-family: 'Nexa';
                font-style: normal;
                font-weight: 400;
                font-size: 15px;
                line-height: 23px;
                color: #2C5B7D;


            }

            &:hover {
                svg {
                    z-index: 2;
                }

                .tooltip-text {
                    display: block;
                    z-index: 1;
                }
            }
        }
    }
}

.table-row {
    min-height: 56px;
    display: flex;
    align-items: center;
    padding: 0px 22px 0px 25px;

    .td {
        font-family: 'Nexa';
        font-style: normal;
        font-weight: 400;
        font-size: 18px;
        line-height: 27px;
        color: #2C5B7D;
    }

    .marked {
        display: flex;
        gap: 10px;

        .letsplan-tooltip {
            display: flex;
            position: relative;
            align-items: flex-start;

            .tooltip-text {
                display: none;
                position: absolute;
                top: -10%;
                right: -5px;
                padding: 22px 16px 16px 15px;
                background-color: white;
                width: 300px;

                font-family: 'Nexa';
                font-style: normal;
                font-weight: 400;
                font-size: 15px;
                line-height: 23px;
                color: #2C5B7D;


            }

            &:hover {
                svg {
                    z-index: 2;
                }

                .tooltip-text {
                    display: block;
                    z-index: 1;
                }
            }
        }
    }
    .menu-bar {
        position: relative;

        > .options {
            position: absolute;
            width: 151px;
            background-color: white;
            right: 0px;
            z-index: 10;

            > .option {
                font-family: 'Nexa';
                font-style: normal;
                font-weight: 400;
                font-size: 15px;
                line-height: 20px;
                color: #135F84;
                padding: 11px 16px 11px 16px;
                border-top: 1px solid #FEF1E7;
                width: 100%;
                text-align: left;
                text-decoration: none;
                display: flex;

                &:hover {
                    background: #BEE7FB;
                    font-weight: 700;
                }
            }
        }
        .options > .option:first-child {
            border-top: none;
        }
    }
}

.main-row {
    background: #E9F7FE;
    &:hover {
        background-color: #D2EEFC;
    }
}

</style>
