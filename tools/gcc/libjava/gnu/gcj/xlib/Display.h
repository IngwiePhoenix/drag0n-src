
// DO NOT EDIT THIS FILE - it is machine generated -*- c++ -*-

#ifndef __gnu_gcj_xlib_Display__
#define __gnu_gcj_xlib_Display__

#pragma interface

#include <java/lang/Object.h>
extern "Java"
{
  namespace gnu
  {
    namespace gcj
    {
        class RawData;
      namespace xlib
      {
          class Display;
          class Screen;
          class Window;
          class XID;
      }
    }
  }
}

class gnu::gcj::xlib::Display : public ::java::lang::Object
{

public:
  Display();
private:
  static void staticInit();
  void init();
public: // actually protected
  virtual void finalize();
  virtual void addXID(jint, ::gnu::gcj::xlib::XID *);
  virtual void removeXID(jint);
public:
  virtual ::gnu::gcj::xlib::Window * getDefaultRootWindow();
  virtual ::gnu::gcj::xlib::XID * getXID(jint);
  virtual ::gnu::gcj::xlib::Window * getWindow(jint);
  virtual ::gnu::gcj::xlib::Screen * getDefaultScreen();
  virtual jint getDefaultScreenNumber();
private:
  jint getDefaultRootWindowXID();
public:
  virtual jint getAtom(::java::lang::String *);
  virtual ::java::lang::String * getAtomName(jint);
private:
  jint internAtom(::java::lang::String *);
public:
  virtual void flush();
public: // actually package-private
  ::gnu::gcj::RawData * __attribute__((aligned(__alignof__( ::java::lang::Object)))) display;
private:
  ::java::util::Dictionary * xids;
  ::java::util::Dictionary * atoms;
public:
  static ::java::lang::Class class$;
};

#endif // __gnu_gcj_xlib_Display__