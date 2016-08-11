/**
 * RcmSwitchUserService
 * @param $http
 * @param rcmLoading
 * @param rcmApiLibService
 * @param rcmEventManager
 * @constructor
 */
var RcmSwitchUserService = function ($http, rcmLoading, rcmApiLibService, rcmEventManager) {

    /**
     * self
     */
    var self = this;

    /**
     * config
     * @type {{suMessage: string}}
     */
    self.config = {
        suMessage: 'User is currently impersonating.'
    };

    /**
     * suData
     * @type {boolean}
     */
    self.suData = {
        isSu: false,
        impersonatedUser: null,
        switchBackMethod: 'auth'
    };

    /**
     * apiPaths
     * @type {{switchUser: string, switchUserBack: string}}
     */
    var apiPaths = {
        switchUser: '/api/rpc/switch-user',
        switchUserBack: '/api/rpc/switch-user-back'
    };

    /**
     * changeSu
     * @param data
     */
    var changeSu = function (data) {
        if (!data) {
            self.suData = {
                isSu: false,
                impersonatedUser: null,
                switchBackMethod: self.suData.switchBackMethod
            };
            return;
        }
        self.suData = data
    };

    /**
     * buildValidData
     * @param data
     * @returns {*}
     */
    var buildValidData = function (data) {
        if (!data) {
            data = {
                isSu: false,
                impersonatedUser: null,
                switchBackMethod: self.suData.switchBackMethod
            };
        }

        return data;
    };

    /**
     * onSuChange
     * @param data
     */
    var onSuChange = function (data) {

        data = buildValidData(data);

        changeSu(data);

        rcmEventManager.trigger(
            'rcmSwitchUserService.suChange',
            data
        );

        setSessionCachedSuData(data);
    };

    /**
     * Gets the cached data from the browser's "session" local storage.
     * This storage clears if the browser is closed.
     *
     * @returns {*}
     */
    function getSessionCachedSuData() {
        var data = null;
        if (typeof(sessionStorage) !== "undefined" && sessionStorage.rcmSwitchUserCachedSu) {
            data = JSON.parse(sessionStorage.rcmSwitchUserCachedSu);
        }
        console.log(data);
        return data;
    }

    /**
     * Sets the cached data in the browser's "session" local storage
     * This storage clears if the browser is closed.
     *
     * @param data
     */
    function setSessionCachedSuData(data) {
        if (typeof(sessionStorage) !== "undefined") {
            sessionStorage.rcmSwitchUserCachedSu = JSON.stringify(data);
        }
    }

    /**
     * getSu
     * @param onSuccess
     * @param onError
     */
    self.getSu = function (onSuccess, onError) {

        var cachedSu = getSessionCachedSuData();

        if (cachedSu) {
            onSuChange(cachedSu);
            onSuccess({data: cachedSu});
            return;
        }

        rcmApiLibService.get(
            {
                url: apiPaths.switchUser,
                loading: function (loading) {
                    var loadingInt = Number(!loading);
                    rcmLoading.setLoading(
                        'rcmSwitchUserService.loading',
                        loadingInt
                    );
                },
                success: function (response) {
                    onSuChange(response.data);
                    onSuccess(response);
                },
                error: function (response) {
                    onSuChange(response.data);
                    onError(response);
                }
            }
        );
    };

    /**
     * switchUser
     * @param switchToUsername
     * @param onSuccess
     * @param onError
     */
    self.switchUser = function (switchToUsername, onSuccess, onError) {

        var data = {
            switchToUsername: switchToUsername
        };

        rcmApiLibService.post(
            {
                url: apiPaths.switchUser,
                data: data,
                loading: function (loading) {
                    var loadingInt = Number(!loading);
                    rcmLoading.setLoading(
                        'rcmSwitchUserService.loading',
                        loadingInt
                    );
                },
                success: function (response, status) {
                    onSuChange(
                        response.data
                    );
                    onSuccess(response, status);
                },
                error: function (response, status) {
                    onSuChange(response.data);
                    onError(response, status);
                }
            }
        );
    };

    /**
     * switchUserBack
     * @param suUserPassword
     * @param onSuccess
     * @param onError
     */
    self.switchUserBack = function (suUserPassword, onSuccess, onError) {

        var data = {
            suUserPassword: suUserPassword
        };

        rcmApiLibService.post(
            {
                url: apiPaths.switchUserBack,
                data: data,
                loading: function (loading) {
                    var loadingInt = Number(!loading);
                    rcmLoading.setLoading(
                        'rcmSwitchUserService.loading',
                        loadingInt
                    );
                },
                success: function (response, status) {
                    onSuChange();
                    onSuccess(response, status);
                },
                error: function (response, status) {
                    onSuChange();
                    onError(response, status);
                }
            }
        );
    };

    /**
     * init
     */
    var init = function () {
        self.getSu(
            function () {
            },
            function () {
            }
        )
    };

    init();
};

/**
 * rcmSwitchUserService
 */
angular.module('rcmSwitchUser').service(
    'rcmSwitchUserService',
    [
        '$http',
        'rcmLoading',
        'rcmApiLibService',
        'rcmEventManager',
        function ($http,
                  rcmLoading,
                  rcmApiLibService,
                  rcmEventManager) {
            return new RcmSwitchUserService(
                $http,
                rcmLoading,
                rcmApiLibService,
                rcmEventManager
            );
        }
    ]
);
